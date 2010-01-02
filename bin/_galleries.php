<?php
Console::debug();
Console::debug('Galleries');
$dbnew->query('TRUNCATE `galleries`');
foreach($dbold->query('SELECT * FROM `gallery_galleries`') as $row) {
//	var_dump($row); break;
	Console::debug(' - Adding '.$row->name);
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