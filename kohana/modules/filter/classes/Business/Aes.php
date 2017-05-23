<?php

//AES加密
class Business_Aes {

	private $encryptKey = "";//AESkey，可自定义
	private $app = "";//AESkey，可自定义

	public function __construct($key = null, $app = null) {
		if (!empty($key)) {
			$this->setKey($key);
		} elseif (!empty($app)) {
			$this->setApp($app);
		}
	}

	public function setKey($key) {
		$this->encryptKey = $key;
		return $this;
	}

	public function setApp($app) {
		$this->app = $app;
		return $this;
	}

	//解密
	public function decrypt($token, $appKey) {
		$iv = hex2bin(substr($token, 0, 32));
		$result = @openssl_decrypt(hex2bin(substr($token,32)), 'AES-128-CBC', $appKey, OPENSSL_RAW_DATA, $iv);
		return $result;
	}

	public function encrypt() {
		if (!$this->encryptKey || !$this->app) {
			return false;
		}
		$iv = openssl_random_pseudo_bytes(16);
		$expire = Kohana::$config->load("default.api.expire");
		list ($s1, $s2) = explode(' ', microtime());
		$time_now = (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
		$encWord = $time_now.'|'.$this->app;
		$token = bin2hex($iv) . bin2hex(openssl_encrypt($encWord, 'AES-128-CBC', $this->encryptKey, OPENSSL_RAW_DATA, $iv));
		return array("token" => $token);
	}

	private function getKey() {
		return $this->encryptKey;
	}

	private function getApp() {
		return $this->app;
	}
}