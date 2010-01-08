<?php defined('SYSPATH') OR die('No direct access allowed.');

class Tracker_Controller extends Controller {
	public function index() {
		$t = new View('tracker/list');
		$t->set('tasks',ORM::factory('task')->find_all());
		$t->render(TRUE);
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
		$task->short = $short;
		$task->long = $long;
		$task->type = $type;
		$task->reporter_id = Auth::instance()->get_user()->id;
		$task->save();
		
		if(request::is_ajax()) die(json_encode(array(
			'result'=>'OK',
			'id'=>$task->id,
			'url'=>$task->generate_url(),
		)));
	}
}