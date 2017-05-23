<?php
/**
 * QMessage 组织消息并发送
 * @author: panchao
 */
class QMessage {

	/**
	 * 组织消息并发送
	 * @param QMessage_Provider $provider
	 * @throws MQ_Exception
	 */
	public static function send(QMessage_Provider $provider) {

		$name = $provider->getName();
		$type = $provider->getMqType();
		$message = $provider->getMessage();
		$format = $provider->getFormat();

		try {
			MQ::factory($type)
				->name($name)
				->data($message)
				->format($format)
				->send();
		} catch (Exception $e) {
			throw new MQ_Exception($e->getMessage());
		}
	}
}