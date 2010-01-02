<?php
Console::debug();
Console::debug('Users');
$dbnew->query('TRUNCATE `users`');
foreach($dbold->query('SELECT * FROM `user`') as $row) {
	Console::debug(' - Adding '.$row->username);
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
