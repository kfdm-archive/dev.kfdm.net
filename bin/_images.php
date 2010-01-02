<?php
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
Console::debug();
Console::debug('Images');
$dbnew->query('TRUNCATE `images`');
foreach($dbold->query('SELECT * FROM `gallery_images`') as $row) {
//	var_dump($row); break;
	Console::debug(' - Adding '.$row->name);
	
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