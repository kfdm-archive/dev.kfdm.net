<?php defined('SYSPATH') OR die('No direct access allowed.');

class Quotes_Controller extends Controller {
	const num_per_page = 9;
	public function index() {
		$this->browse('latest',1);
	}
	public function show($id) {
		if(!empty($_POST)) $this->_rate();
		if(!is_numeric($id)) throw new Exception('INVALID ID');
		$quote = ORM::factory('quote',$id);
		
		$t = new View('quotes/view');
		$t->set('quote',$quote);
		$t->render(TRUE);
	}
	public function browse($order = 'browse', $page = 1) {
		if(!empty($_POST)) $this->_rate();
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