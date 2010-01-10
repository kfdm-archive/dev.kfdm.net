<?php defined('SYSPATH') OR die('No direct access allowed.');

class Project_Model extends ORM {
	protected $has_many = array('tasks');
	public function generate_url() {
		return url::site('/tracker/');
	}
}