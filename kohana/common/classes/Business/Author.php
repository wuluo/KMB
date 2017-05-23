<?php

/**
 * desc
 * @package default
 * @author  qichangchun<qichangchun@gomeplus.com>
 * @date:   2017/5/10
 * @time:   17:30
 */
class Business_Author extends Business {

	public function login($name, $password) {
		$logined = FALSE;
		try {
			$ldaplogined = Business_Login::instance()->ldapLogin($name, $password);
			if ($ldaplogined) {
				$logined = $ldaplogined;
			} else {
				$locallogined = Business_Login::instance()->localLogin($name, $password);
				if ($locallogined) {
					$logined = $locallogined;
				}
			}
		} catch (Exception $e) {
			return $e->getMessage();
		}
		if ($logined) {
			$name = Business_Login::name();
			if ($ldaplogined) {
				Logger::write($name . ' LDAP登录成功');
			} else {
				Logger::write($name . ' Local登录成功');
			}
			return TRUE;
		} else {
				Logger::write($name . ' Local登录成功');
			return FALSE;
		}
	}

	public function logout() {
		try {
			$name = Business_Login::name();
			$return = Business_Login::instance()->logout();
			if ($return) {
				Logger::write($name . ' 退出登录');
				return TRUE;
			}
		} catch (Author_Exception $e) {
			return $e->getMessage();
		}
	}

}