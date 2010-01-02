<?php defined('SYSPATH') OR die('No direct access allowed.');
class Controller extends Controller_Core {
	protected function _use_text_errors() {
		set_error_handler(array('Controller','_error_handler'));
		set_exception_handler(array('Controller','_text_exception_handler'));
	}
	public static function _error_handler($errno, $errstr, $errfile, $errline ) {
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}
	public static function _text_exception_handler($exception) {
		die($exception->getMessage()."\n");
	}
}