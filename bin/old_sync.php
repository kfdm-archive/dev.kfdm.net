<?php
require_once('_cmd.php');

function debug($msg) { echo $msg."\n"; }

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

//Clear old tables
$dbnew->query('TRUNCATE `galleries`');
$dbnew->query('TRUNCATE `images`');
$dbnew->query('TRUNCATE `quotes`');

debug('Galleries');
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
foreach($dbold->query('SELECT * FROM `gallery_images`') as $row) {
//	var_dump($row); break;
	debug(' - Adding '.$row->name);
	$dbnew->insert('images',array(
		'id'				=> $row->id,
		'gallery'			=> $row->gallery,
		'thumb'				=> $row->thumb,
		'image'				=> $row->image,
		'name'				=> $row->name,
		'description'		=> $row->description,
	));
}

debug('');
debug('Quotes');
foreach($dbold->query('SELECT * FROM `quotes`') as $row) {
//	var_dump($row); break;
	debug(' - Adding quote #'.$row->id);
	$dbnew->insert('quotes',array(
		'id'				=> $row->id,
		'quote'				=> $row->quote,
		'postdate'			=> $row->postdate,
	));
}