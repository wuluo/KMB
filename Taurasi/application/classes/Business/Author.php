<?php
/**
 * 业务逻辑类 —— 登录
 */
class Business_Author extends Business {

	public function isLogin() {
		$username = Session::instance()->get('username',null);
		$login = Session::instance()->get('login',null);
		if(!empty($username) && !empty($login)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function login($username, $password) {
		try
		{
			$result = Dao::factory('Author')->getUser($username)->current();
			if($result === null) {
				throw new Exception("用户名错误");
			}
			//从数据库取出盐
			$password = md5(md5($password).$result->_salt);
			if($password != $result->password) {
				throw new Exception("密码错误");
			}
			$nowInfo = array(
				'last_login_ip'=> Misc::getClientIp(),
				'last_login_time'=>time(),
			);
			Session::instance()->set('username',$username);
			Session::instance()->set('title',$result->title);
			Session::instance()->set('logintime',$nowInfo['last_login_time']);
			Session::instance()->set('id',$result->id);
			Session::instance()->set('login',1);
			Dao::factory('Author')->setUser($nowInfo, $result->id);
			Logger::write($username.' 登陆成功');
			return true;
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	
	}
	
	public function logout() {
		try {
			$name = $b = Session::instance()->get('username');
			Session::instance()->delete('username');
			Session::instance()->delete('title');
			Session::instance()->delete('logintime');
			Session::instance()->delete('login');
			Session::instance()->delete('id');
			Cookie::delete(session_name());
			if(Session::instance()->destroy()){
				Logger::write($name.' 退出成功');
				return true;
			}
		} catch(Exception $e) {
			return $e->getMessage();
		}
	}

	
}
