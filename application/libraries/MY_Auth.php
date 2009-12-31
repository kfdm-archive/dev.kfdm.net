<?php defined('SYSPATH') OR die('No direct access allowed.');
class Auth extends Auth_Core {
	public function login($user,$password,$remember = FALSE) {
		if(empty($password)) return FALSE;
		
		if(!is_object($user))
			$user = ORM::factory('user', $user);
		
		if($user->id == 0) return FALSE;
		
		$password = $this->hash_password($password, $user->salt);
		
		return $this->driver->login($user, $password, $remember);
	}
	public function hash_password($password, $salt = FALSE) {
		return $this->hash($salt.$password);
	}
	public function find_salt($password) {
		throw new Exception('DO NOT USE');
	}
}