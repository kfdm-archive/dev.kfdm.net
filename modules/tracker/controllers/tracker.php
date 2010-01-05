<?php defined('SYSPATH') OR die('No direct access allowed.');

class Tracker_Controller extends Controller {
	public function index() {
		View::factory('header')->render(TRUE);
		echo 'Hi';
		View::factory('footer')->render(TRUE);
	}
}