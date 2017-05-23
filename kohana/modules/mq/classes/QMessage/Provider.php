<?php
/**
 * 提供消息信息接口
 * @author: panchao
 */
interface QMessage_Provider {

	/**
	 * mq 类型
	 * @return mixed
	 */
	public function getMqType();

	/**
	 * mq name
	 * @return mixed
	 */
	public function getName();

	/**
	 * mq message
	 * @return mixed
	 */
	public function getMessage();

	/**
	 * mq message format
	 * @return mixed
	 */
	public function getFormat();

	/**
	 * provider message
	 * @param array $values
	 * @return mixed
	 */
	public static function provider(array $values);
}