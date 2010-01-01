<?php defined('SYSPATH') OR die('No direct access allowed.');

class Gallery_Controller extends Controller {
	const num_per_page = 9;
	public function index() {
		$this->show(0);
	}
	public function show($id,$page=0) {
		$this->_tmpl = new View('gallery');
		
		$gallery = ORM::factory('gallery',$id);
		if(isset($_POST['upload_image'])) $this->_upload_image($gallery);
		$subgalleries = ORM::factory('gallery')->where('parent',$id)->find_all();
		
		$pagination = new Pagination(array(
			'base_url'=>$gallery->generate_url(),
			'uri_segment'=>'page',
			'total_items'=>$gallery->image_count(),
			'style'=>'digg',
			'items_per_page'=>Kohana::config('gallery.num_per_page'),
		));
		$offset = $pagination->sql_offset;
		$images = $gallery->images(Kohana::config('gallery.num_per_page'),$offset);
		
		$this->_tmpl->set('gallery',$gallery);
		$this->_tmpl->set('subgalleries',$subgalleries);
		$this->_tmpl->set('pagination',$pagination);
		$this->_tmpl->set('images',$images);
		$this->_tmpl->render(TRUE);
	}
	public function view($id) {
		if(!is_numeric($id)) throw new Exception('INVALID ID');
		$image = ORM::factory('image',$id);
		$gallery = $image->parent_gallery();
		
		$t = new View('image');
		$t->set('gallery',$gallery);
		$t->set('image',$image);
		$t->render(TRUE);
	}
	public function random() {
		$image = ORM::factory('image')->orderby(NULL,'RAND()')->find();
		echo $image->name.' - '.$image->generate_url();
	}
	protected function _append_error($string) {
		$errors = $this->_tmpl->is_set('global_errors')?
			$this->_tmpl->global_errors:
			array();
		$errors[] = $string;
		$this->_tmpl->set_global('global_errors',$errors);
	}
	protected function _upload_image($gallery) {
		if(!Auth::instance()->logged_in('login'))
			return $this->_append_error('Image upload requires login');
		if(empty($_FILES['file']))
			return $this->_append_error('Error with upload');
		if($this->input->post('name')=='')
			$this->_append_error('Missing Image Name');
		if($_FILES['file']['name']=='')
			$this->_append_error('Missing File');
		
		if($this->_tmpl->is_set('global_errors')) return;
		
		$image = ORM::factory('image');
		$image->gallery_id = $gallery->id;
		$image->name = $this->input->post('name');
		$image->mime = $_FILES['file']['type'];
		$image->description = $_FILES['file']['name'];
		$image->size = $_FILES['file']['size'];
		$image->uploaded_on = time();
		$image->uploaded_by = Auth::instance()->get_user()->id;
		if(!$image->validate())
			return $this->_append_error('Error validating Image');
		
		if(!$image->move_uploaded_file($_FILES['file']['tmp_name']))
			return $this->_append_error('Error moving Image');
		
		if(!$image->save())
			return $this->_append_error('Error saving Image');
			
		if(!$image->generate_thumb())
			return $this->_append_error('Error generating thumb');
	}
}
