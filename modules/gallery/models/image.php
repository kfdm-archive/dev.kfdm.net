<?php defined('SYSPATH') OR die('No direct access allowed.');

class Image_Model extends ORM {
	public function generate_url() {
		if($this->id==0) return '/gallery/';
		return url::site('/gallery/view/'.$this->id);
	}
	public function parent_gallery() {
		return ORM::factory('gallery',$this->gallery_id);
	}
	public function thumb() {
		$path = Kohana::config('gallery.thumb_base_path');
		$path .= $this->uploaded_on;
		$path .= '_';
		$path .= $this->uploaded_by;
		$path .= $this->mime();
		return $path;
	}
	public function image() {
		$path = Kohana::config('gallery.image_base_path');
		$path .= $this->uploaded_on;
		$path .= '_';
		$path .= $this->uploaded_by;
		$path .= $this->mime();
		return $path;
	}
	public function validate() {
		return TRUE;
	}
	public function mime() {
		switch($this->mime) {
			case 'image/png':
				return '.png';
			case 'image/jpeg';
				return '.jpg';
			case 'image/gif':
				return '.gif';
			default:
				return '';
		}
	}
	public function prev() {
		return ORM::factory('image')->where(array(
			'gallery_id'=>$this->gallery_id, //Same gallery
			'id<'=>$this->id, //Lower ID
		))->orderby('id','DESC')->find();
	}
	public function next() {
		return ORM::factory('image')->where(array(
			'gallery_id'=>$this->gallery_id, //Same gallery
			'id>'=>$this->id, //Higher ID
		))->orderby('id','ASC')->find();
	}
	public function rotate($degrees) {
		$image = new Image($this->get_image_path());
		$image->rotate($degrees);
		$image->save($this->get_image_path());
		$this->generate_thumb();
	}
	public function generate_thumb() {
		$image = new Image($this->get_image_path());
		$image->resize(200,200);
		$image->save($this->get_thumb_path());
		return TRUE;
	}
	protected function get_image_path() {
		$path = Kohana::config('gallery.image_save_path');
		$path .= $this->uploaded_on;
		$path .= '_';
		$path .= $this->uploaded_by;
		$path .= $this->mime();
		return $path;
	}
	protected function get_thumb_path() {
		$path = Kohana::config('gallery.thumb_save_path');
		$path .= $this->uploaded_on;
		$path .= '_';
		$path .= $this->uploaded_by;
		$path .= $this->mime();
		return $path;
	}
	public function move_uploaded_file($path) {
		return move_uploaded_file($path,$this->get_image_path());
	}
	public function replace_uploaded_file($path) {
		return copy($path,$this->get_image_path());
	}
	public function delete() {
		$image = $this->get_image_path();
		if(is_file($image)) unlink($image);
		$thumb = $this->get_thumb_path();
		if(is_file($thumb)) unlink($thumb);
		return parent::delete();
	}
}