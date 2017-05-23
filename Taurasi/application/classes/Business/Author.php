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
			$roleUser = Dao::factory('RoleUser')->getRoleId($result->id)->current();
			$nowInfo = array(
				'last_login_ip'=> Misc::getClientIp(),
				'last_login_time'=>time(),
			);
			Session::instance()->set('username',$username);
			Session::instance()->set('title',$result->title);
			Session::instance()->set('logintime',$nowInfo['last_login_time']);
			Session::instance()->set('id',$result->id);
			Session::instance()->set('roleid',$roleUser->role_id);
			Session::instance()->set('login',1);
			Dao::factory('Author')->setUser($nowInfo, $result->id);
			//Logger::write($username.' 登陆成功');
			return true;
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	
	}

	public function getMenusAndRules($role_id)
	{
		$showMenus = [];
		$menus = Dao::factory("Menu")->getAllMenus();
		if($role_id > 1){
			$actions = Dao::factory("RoleAction")->getRoleAction($role_id);
			$actionIds = array_column($actions, 'action_id');

			foreach ($menus as $key=>$val){
				if(in_array($val['action_id'], $actionIds)){
					$showMenus[] = $val;
				}
			}
			$showMenus = $this->treeMerge($showMenus);
		}else{
			$actions = Dao::factory("Action")->getAllAction();
			$showMenus = $this->treeMerge($menus);
		}
		return [
			'menus' => $showMenus,
			'rules' =>$actions,
		];
	}
	public function logout() {
		try {
			$name = $b = Session::instance()->get('username');
			Session::instance()->delete('username');
			Session::instance()->delete('title');
			Session::instance()->delete('logintime');
			Session::instance()->delete('login');
			Session::instance()->delete('roleid');
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

	public function treeMerge($array,$pid=0)
	{
		$last=array();
		foreach($array as $k=>$v){
			if($v['pid']==$pid){
				$v['child']=$this->treeMerge($array,$v['id']);
				$last[]=$v;
			}
		}
		return $last;
	}

	
}
