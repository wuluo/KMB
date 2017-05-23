<?php

class Misc {

	static public function encryptLog($text, $key = 'G2f$fsd&sO(7fM@E') {
		# 为 CBC 模式创建随机的初始向量
		$ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		$iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
		$ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $text, MCRYPT_MODE_CBC, $iv);
		# 将初始向量附加在密文之后，以供解密时使用
		$ciphertext = bin2hex($iv . $ciphertext);

		return $ciphertext;
	}

	static public function encrypt($text, $key = '') {
		$ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB);
		$ciphertext = trim(Misc::base64url_encode($ciphertext));
		return $ciphertext;
	}

	static public function decrypt($text, $key = '') {
		$ciphertext = Misc::base64url_decode($text);
		$cleartext = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $ciphertext, MCRYPT_MODE_ECB));
		return $cleartext;
	}

	static function base64url_encode($data) {
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}

	static function base64url_decode($data) {
		return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
	}

	/**
	 * @param $data加密字符
	 * @param $key
	 * @param $iv 向量 16位
	 * @return string
	 */
	static function ext_mcrypt_encrypt($data, $key, $iv) {
		$padding = 16 - (strlen($data) % 16);
		$data .= str_repeat(chr($padding), $padding);
		$csrf = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
		$csrfToken = bin2hex($iv . $csrf);
		return $csrfToken;
	}

	static function ext_mcrypt_decrypt($data, $key, $iv) {
		$data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, hex2bin($data), MCRYPT_MODE_CBC, $iv);
		$padding = ord($data[strlen($data) - 1]);
		return substr(substr($data, 0, -$padding), 16);
	}

	static public function message($message, $redirect = NULL, $delay = 3) {
		echo View::factory('misc/message')
			->set('message', $message)
			->set('redirect', $redirect)
			->set('delay', $delay);
		exit();
	}

	static public function warning($message) {
		echo View::factory('misc/warning')
			->set('message', $message);
		exit();
	}

	static public function toUTF8($string = array(), $fromEncoding = 'GBK') {
		if (is_array($string)) {
			foreach ($string as &$value) {
				self::toUTF8($value);
			}
		} else {
			$string = mb_convert_encoding($string, 'UTF8', $fromEncoding);
		}

		return $string;
	}

	/**
	 * 汉字转拼音
	 * @param string $word
	 * @return string
	 */
	static public function pinyin($word = '') {

		$length = strlen($word);
		if ($length < 3) {
			return $word;
		}

		static $dictionary = array();
		if (!$dictionary) {
			$dictionary = Kohana::$config->load('pinyin')->as_array();
		}

		$pinyins = array();
		$nonchinese = '';
		for ($i = 0; $i < $length; $i++) {
			$ascii = ord($word[$i]);
			if ($ascii < 128) {
				if ($ascii >= 65 && $ascii <= 90) {
					$nonchinese .= strtolower($word[$i]);
				} else {
					$nonchinese .= $word[$i];
				}
			} else {
				if ($nonchinese) {
					$pinyins[] = $nonchinese;
					$nonchinese = '';
				}
				$character = $word[$i];
				$character .= isset($word[++$i]) ? $word[$i] : '';
				$character .= isset($word[++$i]) ? $word[$i] : '';
				$pinyins[] = isset($dictionary[$character]) ? $dictionary[$character] : '';
			}
		}
		if ($nonchinese) {
			$pinyins[] = $nonchinese;
			$nonchinese = '';
		}
		return implode(' ', $pinyins);
	}

	/**
	 *
	 * 获取微博用户信息
	 * @param integer $source appkey
	 * @param integer $userId 用户id
	 * @return array
	 */
	static public function getWeiboUser($source, $userId) {
		$userId = $userId + 0;
		if (!$source || !$userId) {
			return FALSE;
		}
		$url = "http://i2.api.weibo.com/2/users/show.json";
		$postUrl = $url . "?source=" . $source . "&uid=" . $userId;

		// 设置curl请求,后续封装方法
		$ch = curl_init();
		// 设置选项
		curl_setopt($ch, CURLOPT_URL, $postUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		// 执行内容
		$result = curl_exec($ch);
		// 释放curl句柄
		curl_close($ch);

		$result = json_decode($result, TRUE);
		return $result;
	}

	//获取客户端IP
	static public function getClientIp() {
		$ip = 0;
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
			$forwarded = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$ip = array_shift($forwarded);
		} else if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP']) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else {
			if (!empty($_SERVER['REMOTE_ADDR'])) {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
		}
		return $ip;
	}

	/** 美化Json数据
	 * @param  Mixed $data 数据
	 * @param  String $indent 缩进字符，默认4个空格
	 * @return JSON
	 */
	static public function jsonFormat($data, $indent = null) {

		// 将urlencode的内容进行urldecode
		$data = urldecode($data);

		// 缩进处理
		$ret = '';
		$pos = 0;
		$length = strlen($data);
		$indent = isset($indent) ? $indent : '&nbsp;&nbsp;&nbsp;&nbsp;';
		$newline = "<br />";
		$prevchar = '';
		$outofquotes = true;

		for ($i = 0; $i <= $length; $i++) {

			$char = mb_substr($data, $i, 1, 'utf-8');

			if ($char == '"' && $prevchar != '\\') {
				$outofquotes = !$outofquotes;
			} else if (($char == '}' || $char == ']') && $outofquotes) {
				$ret .= $newline;
				$pos--;
				for ($j = 0; $j < $pos; $j++) {
					$ret .= $indent;
				}
			}
			if ($char == '}' || $char == '{' || $char == '[' || $char == ']' || $char == ':' || $char == '"' || $char == ',') {
				$ret .= $char;
			} else {
				$ret .= '<b style="color:#00a65a">' . $char . '</b>';
			}


			if (($char == ',' || $char == '{' || $char == '[') && $outofquotes) {
				$ret .= $newline;
				if ($char == '{' || $char == '[') {
					$pos++;
				}

				for ($j = 0; $j < $pos; $j++) {
					$ret .= $indent;
				}
			}

			$prevchar = $char;
		}

		return $ret;
	}

	/**
	 * 计算是否在指定范围时间点内
	 * @param  integer $period 计算可用时间点
	 * @param  integer $hour 小时
	 * @param  integer $minute 分钟
	 * @param  integer $second 秒
	 * @return Bool
	 */
	static public function keepTime($period = 1, $hour = 0, $minute = 55, $second = 45) {

		$periods = array();
		$mod = 24 % $period;

		if ($mod === 0) {
			if ($period === 1) {
				$periods[] = 24;
			} else {
				$except = 24 / $period;
				for ($i = 0; $i < $except; $i++) {
					$hours = ($i * $period) - 1;
					if ($hours === -1) {
						$periods[] = 23;
					} else {
						$periods[] = $hours;
					}
				}
			}
		} else {
			$periods[] = 24;
		}
		sort($periods);

		if (!in_array($hour, $periods)) {
			return FALSE;
		}
		if ($minute != 59) {
			return FALSE;
		}
		if ($second < 45) {
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * 格式化字节数为人可读的格式
	 * @param type $size
	 * @return type
	 */
	public static function sizeFormat($size) {
		$size = (int) $size;
		if ($size < 1024) {
			return $size . " bytes";
		} else if ($size < (1024 * 1024)) {
			$size = round($size / 1024, 1);
			return $size . " KB";
		} else if ($size < (1024 * 1024 * 1024)) {
			$size = round($size / (1024 * 1024), 1);
			return $size . " MB";
		} else {
			$size = round($size / (1024 * 1024 * 1024), 1);
			return $size . " GB";
		}
	}

	public static function crash($ex) {
		if ((!($ex instanceof Throwable)) && (!($ex instanceof Exception))) {
			throw  new Exception("Misc::crash(),argument 1 must be an Exception Object");
		}
		$loggerCrash = new Log_Database();
		$crashMessage = array(
		    'level' => $ex->getCode(),
		    'file' => $ex->getFile(),
		    'line' => $ex->getLine(),
		    'body' => $ex->getMessage(),
		    'time' => time()
		);
		$loggerCrash->write(array($crashMessage));
	}

	public static function getZipCodeByIp($ip) {
		$ipFilter = filter_var($ip, FILTER_VALIDATE_IP);
		if (!$ipFilter) {
			return false;
		}
		return 110000;
	}

	/*
	 * 生成新的图片地址
	 */

	public static function getImage($imgUrl) {
		if (preg_match("/i\d\.meixincdn/i", $imgUrl, $out)) {
			return str_replace($out[0], 'i' . rand(1, 9) . '.meixincdn', $imgUrl);
		} else {
			return $imgUrl;
		}
	}

	/**
	 * 外网API请求token规则
	 * @param appname string 系统名称
	 * @param user int  用户ID
	 * @return  string
	 */
	public static function getExtraApiToken($appname, $userId) {
		$appkey = Kohana::$config->load('default.appkey');
		return md5($appname . $appkey . $userId);
	}

	/**
	 * 删除redis信息 
	 * @param  array  $instantiationParamet 实例化对象值
	 * @param  string $routeKey  wmq消息消费指定key
	 * @return 
	 */
	public static function deleteRedisCache($instantiationParamet, $routeKey = '') {
		$config = Kohana::$config->load('wmq');
		$URL = $config ['video_event_url'] . '/Mq/video.ssp';
		$header = array(
		    'Token:' . $config ['video_token'],
		    'RouteKey:' . $routeKey,
		    'Content-Type:application/x-www-form-urlencoded'
		);

		$requestQueue = new Curl_Request ();
		$requestQueue->post($URL, $instantiationParamet, $header);
		$code = $requestQueue->code();
		if ($code == 204) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * app管理生成随机盐
	 * @param  appname  string
	 * @param  appkey   string
	 * @param  time     int
	 */
	public static function getAppSalt($appname, $appkey, $time) {
		return substr(md5($appname . $appkey . $time), 0, 16);
	}

	/**
	 * app管理生成appkey
	 * @param  appname  string
	 * @param  time     int
	 */
	public static function getAppkey($appname, $time) {
		return substr(md5($appname . $time), 0, 16);
	}

	public static function formatBytes($bytes, $precision = 1) {
		$units = array('B', 'KB', 'MB', 'GB', 'TB');

		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);
		$bytes /= pow(1024, $pow);

		return round($bytes, $precision) . ' ' . $units[$pow];
	}

	//openssl加解密，替换mcrypt模块
	public static function opensslDecrypt($token, $encryptKey) {
// 		$iv = @hex2bin(substr($token, 0, 32));
		$iv = '123abc78g7o3m51e';
		$result = @openssl_decrypt(@hex2bin(substr($token, 32)), 'AES-128-CBC', $encryptKey, OPENSSL_RAW_DATA, $iv);
		return $result;
	}

	public static function opensslEncrypt($text, $encryptKey) {
// 		$iv = @openssl_random_pseudo_bytes(16);	
		$iv = '123abc78g7o3m51e';
		$token = @bin2hex($iv) . @bin2hex(@openssl_encrypt($text, 'AES-128-CBC', $encryptKey, OPENSSL_RAW_DATA, $iv));
		return $token;
	}
	
	public static function ArraySort(array $List, $by, $order='', $type='') {    
	    foreach ($List as $key => $row) {  
	        $sortby[$key] = $row->$by;  
	    }  
	    if ($order == "DESC") {  
	        if ($type == "num") {  
	            array_multisort($sortby, SORT_DESC, SORT_NUMERIC, $List);  
	        } else {  
	            array_multisort($sortby, SORT_DESC, SORT_STRING, $List);  
	        }  
	    } else {  
	        if ($type == "num") {  
	            array_multisort($sortby, SORT_ASC, SORT_NUMERIC, $List);  
	        } else {  
	            array_multisort($sortby, SORT_ASC, SORT_STRING, $List);  
	        }  
	    }  
	    return $List;  
	}  

}
