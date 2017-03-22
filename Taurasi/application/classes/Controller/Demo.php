<?php

class Controller_Demo extends Controller_Base
{
	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);
	}

	public function action_index()
    {
        $user = Business::factory('Demo')->getUsers();
        $this->display('demo/index', [
            'user' => $user
        ]);
    }

    public function action_firephp()
    {
        FB::instance()
            ->warn('警告')
            ->group('分组1')
            ->info('信息')
            ->groupEnd()
            ->group('分组2')
            ->error('错误')
            ->groupEnd()
            ->table('列表', [
                ['姓名', '性别'],
                ['张三', '男'],
                ['李四', '女']
            ])
            ->trace('堆栈');
    }

    public function action_api()
    {
        $method = $this->request->query('type');
        switch ($method){
            case 'get':
                $res = Business::factory('Req')->api('test',array('a'=>'1'))->method('get')->query(array('b'=>'2'))->execute();
                break;
            case 'post':
                $res = Business::factory('Req')->api('test',array('a'=>'1'))->method('post')->post(array('b'=>'2'))->execute();
                break;
            case 'put':
                $res = Business::factory('Req')->api('test',array('a'=>'1'))->method('put')->query(array('b'=>'2'))->execute();
                break;
            case 'del':
                $res = Business::factory('Req')->api('test',array('a'=>'1'))->method('delete')->query(array('b'=>'2'))->execute();
                break;
            default:
                $res = Business::factory('Req')->api('test',array('a'=>'1'))->method('get')->query(array('b'=>'2'))->execute();
                break;
        }
        print_r($res->body());
    }

    public function action_testcache()
    {
        $data = 'test_cache';
        Cache::instance()->set('foo', $data, 30);
    }

    public function action_test()
	{
		Registry::set('fb',new FB());
		Registry::get('fb')->warn('this is a test');
	}


}
