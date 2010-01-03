<?php defined('SYSPATH') OR die('No direct access allowed.');

class Login_Controller extends Controller {
	public function __construct() {
		parent::__construct();
		$this->auth = Auth::instance();
	}
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
				if($this->auth->login($name,$pass)===TRUE) {
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
		$this->auth->logout();
		url::redirect('/');
	}
	public function admin() {
		if(!$this->auth->logged_in('admin'))
			throw new Kohana_404_Exception();
		$t = new View('login/admin');
		$t->render(TRUE);
	}
	public function users($id = 0) {
		if(!$this->auth->logged_in('admin'))
			throw new Kohana_404_Exception();
		
		if($id == 0) {
			$t = new View('login/users');
			$t->set('users',ORM::factory('user')->find_all());
			return $t->render(TRUE);
		}
		
		$user = ORM::factory('user',$id);
		if(isset($_POST['reset'])) $this->_reset_password($user);
		if(isset($_POST['delete'])) $this->_delete_user($user);
		
		
		$t = new View('login/users_view');
		$t->set('user',$user);
		return $t->render(TRUE);
	}
	protected function _reset_password($user) {
		if($_POST['id'] != $user->id)
			return View::global_error('User id mismatch');
		if(empty($_POST['password']))
			return View::global_error('Invalid Password');
		
		$user->salt = substr(sha1(time()),0,6);
		$user->password = $this->auth->hash_password($user->salt,$_POST['password']);
		
		if(!$user->save())
			return View::global_error('Error saving user');
		View::global_notice('Password Updated');
	}
	protected function _delete_user($user) {
		if($_POST['id'] != $user->id)
			return View::global_error('User id mismatch');
		$user->delete();
		url::redirect('/login/users/');
	}
}