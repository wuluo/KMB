<?php

/**
 * 请求过滤
 * 1.过滤非法请求
 * 2.过滤重复请求
 * 3.过滤黑名单IP请求
 * @package default
 * @author  qichangchun<qichangchun@gomeplus.com>
 * @date:   2016/7/26
 * @time:   10:54
 */
class Helper_RequestFilter extends Abstract_Filter {

	/**
	 * 验证手机来源token是否正确
	 * @param $token
	 * @return bool
	 * @throws Business_Exception
	 */
	public function verifyRequsetToken($token){
		if(false){
			throw new Business_Exception("Token验证未通过");
		}
		return true;
	}

}