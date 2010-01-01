<?php defined('SYSPATH') OR die('No direct access allowed.');

class Image_Model extends ORM {
	public function generate_url() {
		return url::site('/gallery/view/'.$this->id);
	}
	public function parent_gallery() {
		return ORM::factory('gallery',$this->gallery);
	}
	public function thumb() {
		return Kohana::config('gallery.thumb_base_path').$this->thumb;
	}
	public function image() {
		return Kohana::config('gallery.thumb_base_path').$this->image;
	}
}