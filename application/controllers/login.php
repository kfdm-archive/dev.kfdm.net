<?php defined('SYSPATH') OR die('No direct access allowed.');

class Login_Controller extends Controller {
	public function login() {
		$t = new View('login/login');
		
		if(!empty($_POST)) {
			$name = $this->input->post('name');
			$pass = $this->input->post('password');
			$next = $this->input->post('next');
			if(empty($name)) $t->set('name_error','Missing username');
			if(empty($pass)) $t->set('pass_error','Missing password');
			if(empty($next)) $next = '/';
			if(!empty($name) && !empty($pass)) {
				if(Auth::instance()->login($name,$pass)===TRUE) {
					url::redirect($next);
				} else {
					$t->set('login_error','There was an error loggin in');
				}
			}
			$t->set('name',$name);
			$t->set('next',$next);
		} else {
			$t->set('next',isset($_GET['next'])?$_GET['next']:'/');
		}
		$t->render(TRUE);
	}
	public function logout() {
		Auth::instance()->logout();
		url::redirect('/');
	}
}