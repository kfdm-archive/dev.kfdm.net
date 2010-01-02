<?php defined('SYSPATH') OR die('No direct access allowed.');

class Gallery_Controller extends Controller {
	const num_per_page = 9;
	public function index() {
		$this->show(0);
	}
	public function _client($client,$result) {
		if($client!='client') return $result;
		$errors = ($this->_tmpl->is_set('global_errors'))?
			$this->_tmpl->global_errors:
			array();
		var_dump($errors);
		exit();
	}
	public function show($id,$page=0) {
		$this->_tmpl = new View('gallery');
		
		$gallery = ORM::factory('gallery',$id);
		if(isset($_POST['upload_image'])) $this->_client($_POST['upload_image'],$this->_upload_image($gallery));
		if(isset($_POST['link_image'])) $this->_client($_POST['link_image'],$this->_link_image($gallery)); 
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
	protected function _link_image($gallery) {
		if($_POST['link_image']=='client') define('CLIENT_POST',TRUE); 
		if(defined('CLIENT_POST')) $this->_use_text_errors();
		
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
		$image->description = $_POST['file'];
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
		
		if(!defined('CLIENT_POST')) return;
		die($image->generate_url()."\n");
	}
	protected function _upload_image($gallery) {
		if($_POST['upload_image']=='client') define('CLIENT_POST',TRUE); 
		if(defined('CLIENT_POST')) $this->_use_text_errors();
		
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
