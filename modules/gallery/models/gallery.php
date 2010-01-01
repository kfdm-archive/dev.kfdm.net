<?php defined('SYSPATH') OR die('No direct access allowed.');

class Gallery_Model extends ORM {
	public function generate_url() {
		if($this->id==0)
			return url::site('/gallery/');
		return url::site('/gallery/show/'.$this->id);
	}
	public function breadcrumbs() {
		if($this->id == 0) return array();
		return $this->_breadcrumbs($this->parent);
	}
	protected function _breadcrumbs($id) {
		$gallery = ORM::factory('gallery',$id);
		$parents = ($gallery->parent>0)?
			$this->_breadcrumbs($gallery->parent):
			array();
		array_push($parents,$gallery);
		return $parents;
	}
	public function thumb() {
		//If we have a default image, use that one
		if($this->galleryimageid!=0) {
			$image = ORM::factory('image',$this->galleryimageid);
			return $image->thumb();
		}
		//Otherwise find the first image in the gallery and use that
		$image = ORM::factory('image')->where('gallery',$this->id)->find();
		if($image->id==0) return '#';
		return $image->thumb();
	}
	public function image_count() {
		return ORM::factory('image')->where('gallery',$this->id)->count_all();
	}
}