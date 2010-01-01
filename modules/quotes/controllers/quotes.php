<?php defined('SYSPATH') OR die('No direct access allowed.');

class Quotes_Controller extends Controller {
	const num_per_page = 9;
	public function index() {
		$this->show(0);
	}
	public function show($page) {
		$pagination = new Pagination(array(
			'base_url'=>'/quotes/',
			'uri_segment'=>'page',
			'total_items'=>ORM::factory('quote')->count_all(),
			'items_per_page'=>self::num_per_page,
		));
		
		$quotes = ORM::factory('quote')->find_all(self::num_per_page,$pagination->sql_offset);
		
		$t = new View('quotes');
		$t->set('quotes',$quotes);
		$t->set('pagination',$pagination);
		$t->render(TRUE);
	}
}