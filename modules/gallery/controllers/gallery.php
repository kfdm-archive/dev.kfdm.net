<?php defined('SYSPATH') OR die('No direct access allowed.');

class Gallery_Controller extends Controller {
	const num_per_page = 9;
	public function index() {
		$this->show(0);
	}
	public function _client() {
		$errors = ($this->_tmpl->is_set('global_errors'))?
			$this->_tmpl->global_errors:
			array();
		die(json_encode($errors));
	}
	public function show($id) {
		$this->_tmpl = new View('gallery');
		
		$gallery = ORM::factory('gallery',$id);
		if(isset($_POST['upload_image'])) $this->_upload_image($gallery);
		if(isset($_POST['link_image'])) $this->_link_image($gallery);
		if(request::is_ajax()) $this->_client(); //Done with possible ajax calls
		
		$subgalleries = ORM::factory('gallery')->where('parent',$id)->find_all();
		
		$pagination = new Pagination(array(
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
		if(isset($_POST['rotate_left'])) $this->_rotate($image,-90);
		if(isset($_POST['rotate_right'])) $this->_rotate($image,90);
		
		if(isset($_POST['edit_image'])) $this->_edit_image($image,$gallery);
		
		$t = new View('image');
		$t->set('gallery',$gallery);
		$t->set('image',$image);
		$t->render(TRUE);
	}
	protected function _rotate($image,$degrees) {
		if(isset($_POST['username']) && isset($_POST['password']))
			Auth::instance()->login($_POST['username'],$_POST['password']);
		if(!Auth::instance()->logged_in('login'))
			return View::global_error('Image rotate requires login');
		$image->rotate($degrees);
	}
	protected function _edit_image($image) {
		if(isset($_POST['username']) && isset($_POST['password']))
			Auth::instance()->login($_POST['username'],$_POST['password']);
		if(!Auth::instance()->logged_in('login'))
			return View::global_error('Image edit requires login');
		$gallery = $image->parent_gallery();
		$user = Auth::instance()->get_user();
		if($gallery->user_id != 0 && $gallery->user_id != $user->id)
			return View::global_error('User lacks edit for gallery');
		
		$image->name = $_POST['name'];
		$image->description = $_POST['description'];
		if(!$image->save())
			return View::global_error('Error saving');
	}
	public function random($id = 0) {
		$image = ORM::factory('image')->where('gallery_id',$id)->orderby(NULL,'RAND()')->find();
		if(request::is_ajax()) die(json_encode(array(
			'result'=>'OK',
			'id'=>$image->id,
			'name'=>$image->name,
			'url'=>$image->generate_url(),
		)));
		url::redirect($image->generate_url());
	}
	protected function _link_image($gallery) {
		if(request::is_ajax()) $this->_use_text_errors();
		
		if($gallery->id==0)
			return View::global_error('Invalid Gallery id');
		if(isset($_POST['username']) && isset($_POST['password']))
			Auth::instance()->login($_POST['username'],$_POST['password']);
		if(!Auth::instance()->logged_in('login'))
			return View::global_error('Image upload requires login');
		if($gallery->user_id!=0 && $gallery->user_id != Auth::instance()->get_user()->id)
			return View::global_error('User not gallery owner');
		if($this->input->post('name')=='')
			View::global_error('Missing Image Name');
		if($this->input->post('file')=='')
			View::global_error('Missing File Name');
		if(View::errors_set()) return;
		
		$tmp = file::download($_POST['file']);
		
		$image = ORM::factory('image');
		$image->gallery_id = $gallery->id;
		$image->name = $this->input->post('name');
		$image->mime = file::mime($tmp);
		$image->description = isset($_POST['description'])?$_POST['description']:$_POST['file'];
		$image->size = filesize($tmp);
		$image->uploaded_on = time();
		$image->uploaded_by = Auth::instance()->get_user()->id;
		if(!$image->validate())
			return View::global_error('Error validating Image');
		
		if(!$image->replace_uploaded_file($tmp))
			return View::global_error('Error moving Image');
		
		if(!$image->save())
			return View::global_error('Error saving Image');
			
		if(!$image->generate_thumb())
			return View::global_error('Error generating thumb');
		
		$_POST = array();
		
		if(request::is_ajax()) die(json_encode(array(
			'result'=>'OK',
			'id'=>$image->id,
			'name'=>$image->name,
			'url'=>$image->generate_url(),
		)));
	}
	protected function _upload_image($gallery) {
		if(request::is_ajax()) $this->_use_text_errors();
		
		if($gallery->id==0)
			return View::global_error('Invalid Gallery id');
		if(isset($_POST['username']) && isset($_POST['password']))
			Auth::instance()->login($_POST['username'],$_POST['password']);
		if(!Auth::instance()->logged_in('login'))
			return View::global_error('Image upload requires login');
		if($gallery->user_id!=0 && $gallery->user_id != Auth::instance()->get_user()->id)
			return View::global_error('User not gallery owner');
		if(empty($_FILES['file']))
			return View::global_error('Error with upload');
		if($this->input->post('name')=='')
			View::global_error('Missing Image Name');
		if($_FILES['file']['name']=='')
			View::global_error('Missing File');
		
		if(View::errors_set()) return;
		
		$image = ORM::factory('image');
		$image->gallery_id = $gallery->id;
		$image->name = $this->input->post('name');
		$image->mime = $_FILES['file']['type'];
		$image->description = $_FILES['file']['name'];
		$image->size = $_FILES['file']['size'];
		$image->uploaded_on = time();
		$image->uploaded_by = Auth::instance()->get_user()->id;
		if(!$image->validate())
			return View::global_error('Error validating Image');
		
		if(!$image->move_uploaded_file($_FILES['file']['tmp_name']))
			return View::global_error('Error moving Image');
		
		if(!$image->save())
			return View::global_error('Error saving Image');
			
		if(!$image->generate_thumb())
			return View::global_error('Error generating thumb');
			
		$_POST = array();
	}
}
