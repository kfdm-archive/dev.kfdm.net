<?php defined('SYSPATH') OR die('No direct access allowed.');

class Gallery_Controller extends Controller {
	const num_per_page = 9;
	public function index() {
		$this->show(0);
	}
	public function show($id,$page=0) {
		$gallery = ORM::factory('gallery',$id);
		$subgalleries = ORM::factory('gallery')->where('parent',$id)->find_all();
		
		$pagination = new Pagination(array(
			'base_url'=>$gallery->generate_url(),
			'uri_segment'=>'page',
			'total_items'=>$gallery->image_count(),
			'style'=>'digg',
			'items_per_page'=>Kohana::config('gallery.num_per_page'),
		));
		$offset = $pagination->sql_offset;
		$images = ORM::factory('image')->where('gallery',$id)->find_all(Kohana::config('gallery.num_per_page'),$offset);
		
		$t = new View('gallery');
		$t->set('gallery',$gallery);
		$t->set('subgalleries',$subgalleries);
		$t->set('pagination',$pagination);
		$t->set('images',$images);
		$t->render(TRUE);
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
}
