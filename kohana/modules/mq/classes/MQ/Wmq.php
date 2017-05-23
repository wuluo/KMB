<?php
/**
 * WMQ 消息队列
 * @author: panchao
 */
class MQ_Wmq extends MQ {

	/**
	 * instance
	 * @var null
	 */
	private static $_instance = NULL;

	/**
	 * instance
	 * @return object
	 */
	public static function instance() {
		if(self::$_instance === NULL) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * WMQ execute send
	 */
	public function send() {

		$mqInfo = Arr::get(self::$_config, $this->_name, []);
		$token = Arr::get($mqInfo, 'token', '');
		$url = Arr::get($mqInfo, 'url', '');

		if(!$url || !$token) {
			throw new MQ_Exception('wmq group '.$this->_name . ' config error');
		}

		// set http header
		$headers = ['Content-Type:application/x-www-form-urlencoded', 'Token:'.$token];
		if(isset($this->_data['route_key'])) {
			array_push($headers, 'RouteKey:' . $this->_data['route_key']);
			unset($this->_data['route_key']);
		}

		$data = QFormat::factory($this->_format)->execute($this->_data);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if($httpCode != 204) {
			throw new MQ_Exception('请求wmq '.$this->_name.' 接口异常：'.$response);
		}

		return True;
	}
}