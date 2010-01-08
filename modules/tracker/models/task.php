<?php defined('SYSPATH') OR die('No direct access allowed.');

class Task_Model extends ORM {
	protected $belongs_to = array('reporter'=>'user','owner'=>'user');
	public function generate_url() {
		return url::site('/tracker/');
	}
} 