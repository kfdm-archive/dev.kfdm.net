<?php
require_once('_cmd.php');

function debug($msg) { echo $msg."\n"; }
function mime($ext) {
	switch(strtolower($ext)) {
		case 'png':
			return 'image/png';
		case 'jpg':
		case 'jpeg':
			return 'image/jpeg';
		case 'gif':
			return 'image/gif';
	}
}

$dbold = Database::instance('test',array(
	'connection'=>array(
		'type'     => 'mysql',
		'user'     => 'kfdm',
		'pass'     => 'p@ssw0rd',
		'host'     => 'localhost',
		'port'     => FALSE,
		'socket'   => FALSE,
		'database' => 'kfdm_test'
	)));
$dbnew = Database::instance();

debug('Users');
$dbnew->query('TRUNCATE `users`');
foreach($dbold->query('SELECT * FROM `user`') as $row) {
	debug(' - Adding '.$row->username);
	$dbnew->insert('users',array(
		'id'=>$row->id,
		'username'=>$row->username,
		'salt'=>$row->salt,
		'password'=>$row->password,
		//$row->displayname,
		//$row->avatar,
		//$row->active,
		'email'=>$row->email,
		//$row->check,
		'last_login'=>$row->lastlogin,
		'logins'=>0,
	));
}
$user = ORM::factory('user',1);
$user->username = 'kfdm';
$user->save();
$dbnew->insert('roles_users',array( 'user_id'=>1, 'role_id'=>1, ));
$dbnew->insert('roles_users',array( 'user_id'=>34, 'role_id'=>1, ));

debug('Galleries');
$dbnew->query('TRUNCATE `galleries`');
foreach($dbold->query('SELECT * FROM `gallery_galleries`') as $row) {
//	var_dump($row); break;
	debug(' - Adding '.$row->name);
	$dbnew->insert('galleries',array(
		'id'				=> $row->id,
		'parent'			=> $row->parent,
		'user_id'			=> $row->user_id,
		'group_id'			=> 0,
		'name'				=> $row->name,
		'galleryimageid'	=> $row->galleryimageid,
		'private'			=> $row->private,
	));
}

debug('');
debug('Images');
$dbnew->query('TRUNCATE `images`');
foreach($dbold->query('SELECT * FROM `gallery_images`') as $row) {
//	var_dump($row); break;
	debug(' - Adding '.$row->name);
	
	$parts = explode('.',$row->image);
	$mime = mime($parts[1]);
	$parts = explode('_',$parts[0]);
	$uploaded_on = $parts[0];
	$uploaded_by = $parts[1];
	
	$dbnew->insert('images',array(
		'id'				=> $row->id,
		'gallery_id'		=> $row->gallery,
		'name'				=> $row->name,
		'description'		=> $row->description,
		'mime'				=> $mime,
		'uploaded_on'		=> $uploaded_on,
		'uploaded_by'		=> $uploaded_by,
	));
	$old_file = Kohana::config('gallery.import_dir').$row->image;
	if(!file_exists($old_file)) continue;
	$image = ORM::factory('image',$row->id);
	$image->replace_uploaded_file($old_file);
	$image->generate_thumb();
}

debug('');
debug('Quotes');
$dbnew->query('TRUNCATE `quotes`');
foreach($dbold->query('SELECT * FROM `quotes`') as $row) {
//	var_dump($row); break;
	debug(' - Adding quote #'.$row->id);
	$dbnew->insert('quotes',array(
		'id'				=> $row->id,
		'quote'				=> $row->quote,
		'postdate'			=> $row->postdate,
	));
}