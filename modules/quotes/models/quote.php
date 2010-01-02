<?php defined('SYSPATH') OR die('No direct access allowed.');

class Quote_Model extends ORM {
	public function generate_url() {
		return '/quotes/show/'.$this->id;
	}
	public function post_date($format = NULL) {
		if($format===NULL) $format = DATE_RFC822;
		return date($format,$this->postdate);
	}
	public function recalculate() {
		$rating = $this->db->query('SELECT SUM(`rating`) AS `rating` FROM `quote_ratings` WHERE `quote_id` = ?',array($this->id))->current();
		$this->rating = is_numeric($rating->rating)?$rating->rating:0;
		$this->save();
	}
}