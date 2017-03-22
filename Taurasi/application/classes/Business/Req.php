<?php

/*
* +----------------------------------------------------------------------+
* | Copyright (c) 美信 - 信息技术中心
* +----------------------------------------------------------------------+
* | All rights reserved.
* +----------------------------------------------------------------------+
* | @程序名称：Req.php
* +----------------------------------------------------------------------+
* | @程序功能：
* +----------------------------------------------------------------------+
* | Author:wujing01 <wujing01@gomeplus.com>
* +----------------------------------------------------------------------+
* | Date: 2017/2/14 17:21
* +----------------------------------------------------------------------+
*/

class Business_Req extends Business
{
	public function comment_api($api = '', $getParam = array())
	{
		$uri = "http://jh5v.gomeplus.com/";
		$publicParam = array(
			'app_id' => 1,
		);
		$url = $this->set_url($uri . $api, array_merge($publicParam, $getParam));
		return Request::factory($url);
	}

	private function set_url($uri = '', $param = array())
	{
		$paramStr = '';
		if (isset($param)) {
			foreach ($param as $k => $v) {
				$paramStr .= $k . '=' . $v . '&';
			}
			$paramStr = trim($paramStr, '&');
		}
		return $uri . '?' . $paramStr;
	}


}
 
 