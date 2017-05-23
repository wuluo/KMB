<?php
class Controller_Author extends Controller {
	//登陆页面
	public function action_index() {
		if(Business::factory('Author')->isLogin()) {
			Controller::redirect('/');
		}
		$this->response->body(View::factory("login/index"));
	}

	public function action_login() {
		if(Business::factory('Author')->isLogin()) {
			Controller::redirect('/');
		}
		$username = Arr::get($_POST, 'username', '');
		$password = Arr::get($_POST, 'password', '');
		$loginResult = Business::factory('Author')->login($username, $password);
		if($loginResult === TRUE) {
			return Controller::redirect('/');
		} else {
			return Misc::message($loginResult, 'author');
		}
	}
	
	public function action_logout() {

		$logoutResult = Business::factory('Author')->logout();

		if($logoutResult === TRUE) {
			Controller::redirect('/author');
		} else {
			Misc::message($logoutResult, 'author');
		}
	}
}
