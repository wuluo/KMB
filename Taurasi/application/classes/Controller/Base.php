<?php

class Controller_Base extends Controller
{
    public $_view ;
	/**
	 * 在执行action前执行该方法
	 */
	public function before()
	{
		$this->_view = new View();
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
    public function display($tpl = "")
    {
        $current_controller = strtolower($this->request->controller());
        $current_action = strtolower($this->request->action());

        if (empty($tpl)){
            $tpl = $current_controller.'/'.$current_action;
        }
		$this->response->body(View::factory($tpl));
    }



    /**
     * 在执行action后执行该方法
     */
    public function after()
    {

    }

    public function getMenusAndRules()
    {

    }
}
