<?php
require_once('_cmd.php');
define('GALLERY_NAME','test');

$gallery = ORM::factory('gallery')->where('name',GALLERY_NAME)->find();
if($gallery->id == 0) {
	Console::debug('No gallery named '.GALLERY_NAME.' found');
	exit();
}

Console::debug('Clearing Gallery: '.$gallery->name);
foreach($gallery->images() as $image) {
	Console::debug('Deleting Image: '.$image->name);
	$image->delete();
}
