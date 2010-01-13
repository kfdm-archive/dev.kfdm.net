<?php defined('SYSPATH') OR die('No direct access allowed.');

class Gallery_Controller extends Controller {
	const num_per_page = 9;
	public function __construct() {
		parent::__construct();
		if(request::is_ajax()) $this->_use_json_errors();
	}
	public function index() {
		$this->show(0);
	}
	protected function _client() {
		$errors = ($this->_tmpl->is_set('global_errors'))?
			$this->_tmpl->global_errors:
			array();
		$errors['result'] = 'ERRORS';
		die(json_encode($errors));
	}
	public function show($id) {
		$this->_tmpl = new View('gallery');
		
		$gallery = ORM::factory('gallery',$id);
		if(isset($_POST['upload_image'])) $this->_image_upload($gallery);
		if(isset($_POST['link_image'])) $this->_image_link($gallery);
		if(isset($_POST['search'])) $this->_search($gallery);
		if(isset($_POST['new_sub_gallery'])) $this->_gallery_create($gallery);
		if(isset($_POST['delete_gallery'])) $this->_gallery_delete($gallery);
		if(request::is_ajax()) $this->_client(); //Done with possible ajax calls
		
		$subgalleries = $gallery->sub_galleries();
		
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
		if(isset($_POST['rotate_left'])) $this->_image_rotate($image,-90);
		if(isset($_POST['rotate_right'])) $this->_image_rotate($image,90);
		
		if(isset($_POST['edit_image'])) $this->_image_edit($image,$gallery);
		if(isset($_POST['delete_image'])) $this->_image_delete($image,$gallery);
		if(isset($_POST['move_image'])) $this->_image_move($image,$gallery);
		if(isset($_POST['default_image'])) $this->_image_default($image,$gallery);
		if(request::is_ajax()) $this->_client(); //Done with possible ajax calls
		
		$t = new View('image');
		$t->set('gallery',$gallery);
		$t->set('image',$image);
		$t->render(TRUE);
	}
	protected function _gallery_create($parent) {
		if(isset($_POST['username']) && isset($_POST['password']))
			Auth::instance()->login($_POST['username'],$_POST['password']);
		if(!Auth::instance()->logged_in('gallery_create'))
			return View::global_error('Lacks Gallery Create Permissions');
		$user = Auth::instance()->get_user();
		if($parent->user_id != 0 && $parent->user_id != $user->id)
			return View::global_error('User lacks edit for gallery');
		if($this->input->post('name')=='')
			return View::global_error('Missing Gallery Name');
		
		$gallery = ORM::factory('gallery');
		$gallery->name = $_POST['name'];
		$gallery->user_id = $user->id;
		$gallery->parent = $parent->id;
		if(!$gallery->save())
			return View::global_error('Error saving gallery');
			
		url::redirect($gallery->generate_url());
	}
	protected function _gallery_delete($gallery) {
		if(isset($_POST['username']) && isset($_POST['password']))
			Auth::instance()->login($_POST['username'],$_POST['password']);
		if(!Auth::instance()->logged_in('gallery_delete'))
			return View::global_error('Lacks Gallery Delete Permissions');
		$user = Auth::instance()->get_user();
		if($gallery->user_id != 0 && $gallery->user_id != $user->id)
			return View::global_error('User lacks edit for gallery');
		
		$parent = $gallery->parent_gallery();
		$gallery->delete();
		url::redirect($parent->generate_url());
		return View::global_error('DELETING GALLERY '.$gallery->name);
	}
	/**
	 * Return a list of galleries for a select box
	 * @param unknown_type $gallery
	 * @return unknown_type
	 */
	protected function _gallery_select($current) {
		$cache = Cache::instance();
		$key = '_gallery_select';
		$galleries = $cache->get($key);
		if(is_array($galleries)) return $galleries;
		
		$galleries = array();
		$galleries[0] = 'root';
		foreach($this->_gallery_select_helper(ORM::factory('gallery'),1) as $k=>$v)
				$galleries[$k] = $v;
				
		$cache->set($key,$galleries);
		return $galleries;
	}
	protected function _gallery_select_helper($current,$tab) {
		$galleries = array();
		$tabs = str_repeat('-',$tab);
		foreach($current->sub_galleries() as $gallery) {
			$galleries[$gallery->id] = $tabs.$gallery->name;
			foreach($this->_gallery_select_helper($gallery,($tab+1)) as $k=>$v)
				$galleries[$k] = $v;
		}
		return $galleries;
	}
	
	protected function _image_rotate($image,$degrees) {
		if(isset($_POST['username']) && isset($_POST['password']))
			Auth::instance()->login($_POST['username'],$_POST['password']);
		if(!Auth::instance()->logged_in('login'))
			return View::global_error('Image rotate requires login');
		$image->rotate($degrees);
	}
	protected function _image_edit($image) {
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
	protected function _image_move($image,$src) {
		if(isset($_POST['username']) && isset($_POST['password']))
			Auth::instance()->login($_POST['username'],$_POST['password']);
		if(!Auth::instance()->logged_in('login'))
			return View::global_error('Image edit requires login');
		if(!is_numeric($this->input->post('gallery')))
			return View::global_error('Invalid dest id');
		$user = Auth::instance()->get_user();
		if($src->user_id != 0 && $src->user_id != $user->id)
			return View::global_error('User lacks edit for gallery '.$src->name);
		$dst = ORM::factory('gallery',$_POST['gallery']);
		if($dst->user_id != 0 && $dst->user_id != $user->id)
			return View::global_error('User lacks edit for gallery '.$dst->name);
		
		$image->gallery_id = $dst->id;
		if(!$image->save())
			return View::global_error('Error moving image');
		url::redirect($image->generate_url());
	}
	protected function _image_default($image,$gallery) {
		if(isset($_POST['username']) && isset($_POST['password']))
			Auth::instance()->login($_POST['username'],$_POST['password']);
		if(!Auth::instance()->logged_in('login'))
			return View::global_error('Image edit requires login');
		if(!is_numeric($this->input->post('gallery')))
			return View::global_error('Invalid dest id');
		$user = Auth::instance()->get_user();
		if($gallery->user_id != 0 && $gallery->user_id != $user->id)
			return View::global_error('User lacks edit for gallery '.$gallery->name);
		
		$gallery->galleryimageid = $image->id;
		if(!$gallery->save())
			return View::global_error('Error setting default image');
	}
	protected function _image_delete($image) {
		if(isset($_POST['username']) && isset($_POST['password']))
			Auth::instance()->login($_POST['username'],$_POST['password']);
		if(!Auth::instance()->logged_in('login'))
			return View::global_error('Image edit requires login');
		$gallery = $image->parent_gallery();
		$user = Auth::instance()->get_user();
		if($gallery->user_id != 0 && $gallery->user_id != $user->id)
			return View::global_error('User lacks edit for gallery');
		
		$image->delete();
		url::redirect($gallery->generate_url());
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
	protected function _image_link($gallery) {
		if(request::is_ajax()) $this->_use_json_errors();
		
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
	protected function _search() {
		if(request::is_ajax()) $this->_use_json_errors();
		$query = $_POST['search'];
		$images = ORM::factory('image')->like('name',$query)->find_all();
		$result = array('images'=>array());
		foreach($images as $i)
			$result['images'][] = array(
				'id'=>$i->id,
				'name'=>$i->name,
				'url'=>$i->generate_url(),
			);
		$result['result'] = 'OK';
		$result['count'] = count($result['images']);
		if(request::is_ajax()) die(json_encode($result));
	}
	protected function _image_upload($gallery) {
		if(request::is_ajax()) $this->_use_json_errors();
		
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
		$image->mime = file::mime($_FILES['file']['tmp_name']);
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
		
		if(request::is_ajax()) die(json_encode(array(
			'result'=>'OK',
			'id'=>$image->id,
			'name'=>$image->name,
			'url'=>$image->generate_url(),
		)));
	}
}
