<?php defined('SYSPATH') OR die('No direct access allowed.');

class Task_Model extends ORM {
	protected $belongs_to = array('project','reporter'=>'user','owner'=>'user');
	public function generate_url() {
		return url::site('/tracker/task/'.$this->id);
	}
	public function parent_project() {
		return ORM::factory('project',$this->project_id);
	}
} 