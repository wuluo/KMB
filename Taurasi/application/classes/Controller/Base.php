<?php

class Controller_Base extends Controller
{
	public $_view ;
	public $debug = false;
	public $userId = 0;
	public $userInfo = [];
	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);
	}
	public function assign($name, $value = null)
	{
		$this->_view->set($name, $value);
		return $this;
	}

	public function render($tpl, array $vars = [])
	{
		return $this->_view->set($vars)->render($tpl);
	}

	/**
	 * 显示视图方法
	 *
	 * @param $version_key 指定当前视图需要加载的静态资源
	 * @param string $tpl 当前视图模板路径
	 * @param array $vars 传递给模板的变量
	 */
	public function display($tpl = "", array $vars = [])
	{
		$current_controller = strtolower($this->request->controller());
		$current_action = strtolower($this->request->action());

		if (empty($tpl)){
			$tpl = $current_controller.'/'.$current_action;
		}
		$this->_view->set_filename($tpl);
		if (!empty($vars)){
			$this->_view->set($vars);
		}
		//获取后台登录用户相关信息
		$userinfo = [
			'username'=> Session::instance()->get('username'),
			'title'=> Session::instance()->get('title'),
			'logintime'=> Session::instance()->get('logintime'),
			'id'=> Session::instance()->get('id'),
			'roleid'=> Session::instance()->get('roleid'),
			'login'=> Session::instance()->get('login'),
		];
		$RoleMenus = Business::factory("Author")->getMenusAndRules($userinfo['roleid']);
		$this->assign([
			'userinfo'=>$userinfo,
			'menus'=>$RoleMenus['menus'],
			'rules'=>$RoleMenus['rules'],
			'nowUrl'=>'/'.$current_controller.'/'.$current_action,
		]);
		$this->response->headers('cache-control', 'no-store, must-revalidate');
		$this->response->headers('Pragma', 'no-cache');
		$this->response->body($this->_view);
	}



	/**
	 * 在执行action前执行该方法
	 */
	public function before()
	{
		$this->_view = new View();
	}

	/**
	 * 在执行action后执行该方法
	 */
	public function after()
	{

	}


}
