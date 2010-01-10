<?php defined('SYSPATH') OR die('No direct access allowed.');

class Tracker_Controller extends Controller {
	public function index() {
		$t = new View('tracker/list');
		$t->set('projects',ORM::factory('project')->find_all());
		$t->render(TRUE);
	}
	public function json() {
		if(request::is_ajax()) $this->_use_json_errors();
		$tasks = array();
		foreach(ORM::factory('task')->find_all() as $task)
			$tasks[] = array(
				'id'=>$task->id,
				'short'=>$task->title,
				'long'=>$task->notes,
				'url'=>$task->generate_url(),
			);
		die(json_encode($tasks));
	}
	public function report() {
		if(request::is_ajax()) $this->_use_json_errors();
		
		if(isset($_POST['username']) && isset($_POST['password']))
			Auth::instance()->login($_POST['username'],$_POST['password']);
		if(!Auth::instance()->logged_in('login'))
			throw new Exception('Bug Report requires login');
			
		$short	= strip_tags($this->input->post('short'));
		$long	= strip_tags($this->input->post('long'));
		$type	= strtolower(strip_tags($this->input->post('type')));
		
		if($short=='') throw new Exception('EMPTY BUG');
		if(!in_array($type,array('request','bug'))) throw new Exception('INVALID BUG TYPE');
		
		$task = ORM::factory('task');
		$task->title = $short;
		$task->notes = $long;
		$task->type = $type;
		$task->project_id = 1;
		$task->save();
		
		if(request::is_ajax()) die(json_encode(array(
			'result'=>'OK',
			'id'=>$task->id,
			'url'=>$task->generate_url(),
		)));
	}
}