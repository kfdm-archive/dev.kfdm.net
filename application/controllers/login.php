<?php defined('SYSPATH') OR die('No direct access allowed.');

class Login_Controller extends Controller {
	public function login() {
		$t = new View('login/login');
		if(!empty($_POST)) {
			$name = $this->input->post('name');
			$pass = $this->input->post('password');
			if(empty($name)) $t->set('name_error','Missing username');
			if(empty($pass)) $t->set('pass_error','Missing password');
			if(!empty($name) && !empty($pass)) {
				if(Auth::instance()->login($name,$pass)===TRUE) {
					url::redirect('/');
				} else {
					$t->set('login_error','There was an error loggin in');
				}
			}
			$t->set('name',$name);
		}
		$t->render(TRUE);
	}
	public function logout() {
		Auth::instance()->logout();
		url::redirect('/');
	}
}