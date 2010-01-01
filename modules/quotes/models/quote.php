<?php defined('SYSPATH') OR die('No direct access allowed.');

class Quote_Model extends ORM {
	public function generate_url() {
		return '/quotes/show/'.$this->id;
	}
}