<?php defined('SYSPATH') OR die('No direct access allowed.');

class Gallery_Model extends ORM {
	public function parent_gallery() {
		return ORM::factory('gallery',$this->parent);
	}
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
		$image = ORM::factory('image')->where('gallery_id',$this->id)->find();
		if($image->id==0) return '#';
		return $image->thumb();
	}
	public function image_count() {
		return ORM::factory('image')->where('gallery_id',$this->id)->count_all();
	}
	public function is_visible() {
		$private = $this->is_private();
		if(!$private) return TRUE;
		$auth = Auth::instance()->logged_in('login');
		if(!$auth) return FALSE;
		return TRUE;
	}
	public function is_private() {
		return ($this->private)?
			TRUE:
			FALSE;
	}
	/**
	 * 
	 * @see ORM::find_all
	 * @return mixed Image_Model
	 */
	public function images($limit = NULL, $offset = NULL) {
		return ORM::factory('image')->where('gallery_id',$this->id)->find_all($limit,$offset);
	}
	public function sub_galleries() {
		return ORM::factory('gallery')->where('parent',$this->id)->find_all();
	}
	public function delete($id = NULL) {
		if($id!==NULL) throw new Exception();
		foreach($this->sub_galleries() as $gallery)
			if(!$gallery->delete())
				throw new Exception('ERROR DELETING Gallery:'.$gallery->id);
		foreach($this->images() as $image)
			if(!$image->delete())
				throw new Exception('ERROR DELETING Image:'.$image->id);
		return parent::delete();
	}
}