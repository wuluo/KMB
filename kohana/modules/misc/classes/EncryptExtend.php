<?php

/**
 * 视频加密工具
 * @package default
 * @author  qichangchun<qichangchun@gomeplus.com>
 * @date:   2016/7/28
 * @time:   9:52
 */
class EncryptExtend {
	/**
	 * 生产加密字符串
	 * @param $salt 密钥
	 * @param $param mixed 加密拼接参数
	 * @param null $separator 拼接参数的分隔符
	 */
	public static function md5Encrypt($salt, $param, $before = true, $separator = null) {
		$encryptStr = "";//加密之前的字符串
		if ($separator == null) {
			$separator = '';
		}
		$encryptStr = is_array($param) ? implode($separator, $param) : $param;
		$encryptStr = $before ? $salt.$separator.$encryptStr:$encryptStr.$salt.$separator;
		return md5($encryptStr);
	}

	public static function salt($str,$after = ""){
		return substr(md5($str),0,6).$after;
	}

}