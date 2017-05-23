<?php

//AES加密
class Aes {

	private static $encryptKey = "";//AESkey，可自定义

	public static function setKey($key)
    {
		self::$encryptKey = $key;
	}

	//解密
	public static function decrypt($encryptStr, $iv)
    {
	    if (empty(self::$encryptKey)){
	        return false;
        }
		$encryptKey = self::$encryptKey;
//		$encryptStr = hex2bin("d7acd35385dfcbc7703daa234c5550b3");
//		$tmp = base64_decode("ENKuB1q2xyc89MZfVbfhVA==");
		$decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $encryptKey, $encryptStr, MCRYPT_MODE_CBC, $iv);
		return trim(substr($decrypted,0,13));
	}

	public static function encrypt($encryptStr)
    {
        $encryptKey = self::$encryptKey;

        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);

        $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $encryptKey, $encryptStr, MCRYPT_MODE_CBC, $iv);
        return bin2hex($iv).bin2hex($encrypted);
    }

}