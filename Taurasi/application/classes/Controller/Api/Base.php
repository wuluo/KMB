<?php

class Controller_Api_Base extends Controller
{

    public function before()
    {
        $this->response->headers('Content-Type', 'application/json;charset=utf-8');
    }

	/*
	 *@检测接口异常
	 *
	 * param : [array] - $interfaceData
	 * author: snow wolf
	 *
	 *@return: bool - true 异常
	 */
	protected function _check_exception($interfaceData)
	{
		if(isset($interfaceData['message']) && !empty($interfaceData['message']))
		{
			return true;
		}
		return false;
	}

	/*
	 *@ 控制发起请求
	 * param : [string] - $method - 资源操作符
	 *         [array ] - $params - 参数
	 *
	 *@author: snow wolf
	 *@return: array
	 */
	protected function _curlrequest($method='get',$url='',array $params)
	{
		try{
			if(empty($method) || empty($url)) throw new HTTP_Exception('Invalid argument');
			switch ($method){
				case 'get':
					$request = Request::factory($url)->method($method)->query($params)->execute();
					break;
				case 'post':
					$request = Request::factory($url)->method($method)->post($params)->execute();
					break;
				case 'put':
					$request = Request::factory($url)->method($method)->body($params)->execute();
					break;
				case 'delete':
					$request = Request::factory($url)->method($method)->body($params)->execute();
					break;
				default:
					$request = Request::factory($url)->method($method)->query($params)->execute();
					break;
			}
		} catch (HTTP_Exception $e) {
			echo $e->getMessage();die;
		} catch (Exception $e) {
			echo $e->getMessage();die;
		}
		return ['status'=>$request->status(),'body'=>json_decode($request->body(),true)];
	}
}
