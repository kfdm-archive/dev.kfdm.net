<?php defined('SYSPATH') OR die('No direct access allowed.');

class Quotes_Controller extends Controller {
	const num_per_page = 9;
	public function index() {
		$this->browse('latest',1);
	}
	public function show($id) {
		if(isset($_POST['rating'])) $this->_rate();
		if(request::is_ajax()) die(); //Done with possible ajax calls
		if(!is_numeric($id)) throw new Exception('INVALID ID');
		$quote = ORM::factory('quote',$id);
		
		$t = new View('quotes/view');
		$t->set('quote',$quote);
		$t->render(TRUE);
	}
	public function browse($order = 'browse', $page = 1) {
		if(isset($_POST['rating'])) $this->_rate();
		if(isset($_POST['submit_quote'])) $this->_submit();
		if(request::is_ajax()) die(); //Done with possible ajax calls
		
		$pagination = new Pagination(array(
			//'base_url'=>'quotes/latest/page',
			'uri_segment'=>'page',
			'total_items'=>ORM::factory('quote')->count_all(),
			'items_per_page'=>self::num_per_page,
		));
		
		switch($order) {
			case 'latest':
				$quotes = ORM::factory('quote')->orderby('id','DESC')->find_all(self::num_per_page,$pagination->sql_offset);
			break;
			case 'rating':
				$quotes = ORM::factory('quote')->orderby('rating','DESC')->find_all(self::num_per_page,$pagination->sql_offset);
			break;
			default:
				$quotes = ORM::factory('quote')->find_all(self::num_per_page,$pagination->sql_offset);
		}
		
		$t = new View('quotes');
		$t->set('quotes',$quotes);
		$t->set('pagination',$pagination);
		$t->render(TRUE);
	}
	public function random() {
		$quote = ORM::factory('quote')->orderby(NULL,'RAND()')->find();
		header('Content-type: text/plain');
		die(json_encode(array(
			'result'=>'OK',
			'id'=>$quote->id,
			'quote'=>$quote->quote,
			'rating'=>$quote->rating,
			'url'=>$quote->generate_url(),
		)));
	}
	public function search() {
		if(request::is_ajax()) $this->_use_text_errors();
		$query = $_POST['search'];
		$quotes = ORM::factory('quote')->like('quote',$query)->find_all();
		$result = array();
		foreach($quotes as $q)
			$result['quotes'][] = array(
				'id'=>$q->id,
				'quote'=>$q->quote,
				'rating'=>$q->rating,
				'url'=>$q->generate_url(),
			);
		$result['result'] = 'OK';
		$result['count'] = count($result['quotes']);
		if(request::is_ajax()) die(json_encode($result));
	}
	protected function _submit() {
		if($_POST['submit_quote']=='client') define('CLIENT_POST',TRUE); 
		if(defined('CLIENT_POST')) $this->_use_text_errors();
		
		if(isset($_POST['username']) && isset($_POST['password']))
			Auth::instance()->login($_POST['username'],$_POST['password']);
		if(!Auth::instance()->logged_in('login'))
			throw new Exception('Quote Submit requires login');
		if($this->input->post('quote')=='')
			View::global_error('Missing Quote');
			
		$quote = ORM::factory('quote');
		$quote->quote = $this->input->post('quote');
		$quote->postdate = time();
		$quote->submitted_by = Auth::instance()->get_user()->id;
		
		if(!$quote->save()) throw new Exception('ERROR SAVING QUOTE');
		
		header('Content-type: text/plain');
		die(json_encode(array(
			'result'=>'OK',
			'id'=>$quote->id,
			'url'=>$quote->generate_url(),
		)));
	}
	protected function _rate() {
		if(!is_numeric($this->input->post('id')))
			View::global_error('Invalid Quote ID');
		if($this->input->post('rating')=='')
			View::global_error('Invalid Rating');
		if(View::errors_set()) return;
		
		$quote = ORM::factory('quote',$_POST['id']);
		if($quote->id == 0)
			return View::global_error('Missing Quote');
		
		$rating = ORM::factory('quote_rating')->where(array(
			'quote_id'=>$quote->id,
			'ip'=>$this->input->ip_address(),
		))->find();
		$rating->quote_id = $quote->id;
		$rating->ip = $this->input->ip_address();
			
		switch($_POST['rating']) {
			case '+':
				$rating->rating = +1;
			break;
			case '-':
				$rating->rating = -1;
			break;
		}
		$rating->save();
		$quote->recalculate();
	}
}