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

	//测试用账号生成 账号admin,密码admin
	public function action_test_insert() {
		$salt = substr(md5(time()), 0, 4);
		$password = $salt.md5($salt.'admin');
		list($insert_id,$rows) = DB::query(Database::INSERT,"insert into codec_user(username,password) values('admin','$password')")->execute();
		var_dump($insert_id);
	}
}
