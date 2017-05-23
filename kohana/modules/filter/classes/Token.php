<?php

class Token extends Abstract_Filter {

	public static function encypt($app, $appKey) {
		if (!$app || !$appKey) {
			throw new Exception("token param not exist");
		}
		$token = "";
		try {
			$token = Business::factory('Aes')
				->setKey($appKey)
				->setApp($app)
				->encrypt();
		} catch (Exception $e) {
			return false;
		}
		return $token;
	}
}