<?php defined('SYSPATH') OR die('No direct access allowed.');
class url extends url_Core {
	public static function site($uri = '', $protocol = FALSE) {
		$url = parent::site($uri,$protocol);
		if(!request::is_ajax()) return $url;
		return str_replace('bakayarou.kungfudiscomonkey.net','b.kf-dm.net',$url);
	}
}