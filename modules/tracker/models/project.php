<?php defined('SYSPATH') OR die('No direct access allowed.');

class Project_Model extends ORM {
	protected $has_many = array('tasks');
	public function generate_url() {
		return url::site('/tracker/project/'.$this->id);
	}
	public function recalculate() {
		$count = $this->db->query('SELECT COUNT(*) AS `task_count` FROM `tasks` WHERE `project_id` = ?',array($this->id))->current();
		$this->task_count = is_numeric($count->task_count)?$count->task_count:0;
		$this->save();
	}
}