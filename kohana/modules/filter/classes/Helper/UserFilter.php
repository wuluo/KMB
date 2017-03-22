<?php

/**
 * 用户访问过滤器
 * 1.黑名单用户
 * 2.权重较低用户
 * @package default
 * @author  qichangchun<qichangchun@gomeplus.com>
 * @date:   2016/7/26
 * @time:   13:55
 */
class Helper_UserFilter extends Abstract_Filter {
	public function test1() {
		echo 'test1_function success';
	}

	public function test2($param1, $param2) {
		echo 'Helper_UserFilter----test2';
		echo 'test1_function success:' . $param1 . '-->' . $param2;
		throw new Business_Exception("用户在黑名单中!!!");
	}
}