<?php defined('SYSPATH') OR die('No direct access allowed.');
class View extends View_Core {
	public static function global_error($message) {
		if(!isset(self::$kohana_global_data['global_errors']))
			self::$kohana_global_data['global_errors'] = array();
		self::$kohana_global_data['global_errors'][] = $message;
	}
	public static function global_notice($message) {
		if(!isset(self::$kohana_global_data['global_notices']))
			self::$kohana_global_data['global_notices'] = array();
		self::$kohana_global_data['global_notices'][] = $message;
	}
	public static function set_error($name, $message) {
		if(!isset(self::$kohana_global_data['errors']))
			self::$kohana_global_data['errors'] = array();
		self::$kohana_global_data['errors'][$name] = $message;
	}
	public static function errors_set() {
		if(isset(self::$kohana_global_data['global_errors']))
			return TRUE;
		if(isset(self::$kohana_global_data['errors']))
			return TRUE;
		return FALSE;
	}
}