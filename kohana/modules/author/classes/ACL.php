<?php
/**
 * acl控制类
 * 	实现校验当前用户的对系统资源的权限鉴别
 * @author Sundj
 *
 */
class ACL {
	
	protected $_privilege = NULL;
	
	static protected $_instance = NULL;
	
	static public function instance() {
		if(self::$_instance === NULL) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	static public function all() {
		return Author::privileges();
	}
	
	static public function access($controller, $action = NULL) {
		if(Author::accountId() == 1) {
			return TRUE;
		}
		
		$domains = array($_SERVER['SERVER_NAME'], $_SERVER['HTTP_HOST']);
		$controller = strtolower($controller);
		$action = strtolower($action);

		$privileges = Author::privileges();

		foreach($privileges as $privilege) {
			if(PORTAL == $privilege->getPortal()
				&& $controller == strtolower($privilege->getController())
				&& $action == strtolower($privilege->getAction())) {
				ACL::instance()->privilege($privilege);
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
	public function __construct(Model_Privilege $privilege = NULL) {
		if($privilege !== NULL) {
			$this->_privilege = $privilege;
		}
	}
	
	public function privilege(Model_Privilege $privilege = NULL) {
		if($privilege !== NULL) {
			$this->_privilege = $privilege;
		}
		return $privilege;
	}
}