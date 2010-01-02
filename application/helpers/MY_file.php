<?php defined('SYSPATH') OR die('No direct access allowed.');
class file extends file_Core {
	public static function download($url, $file=NULL) {
		if($file===NULL) $file = tempnam('/tmp','');
		file_put_contents($file,file_get_contents($url));
		return $file;
	}
}