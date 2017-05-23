<?php
/**
 * MQ Message Format
 * @author: panchao
 */
abstract class QFormat {

	const FORMAT_JSON = 1;

	const FORMAT_XML = 2;

	const FORMAT_ARRAY = 3;

	/**
	 * factory
	 * @param $format
	 * @return mixed
	 * @throws MQ_Exception
	 */
	public static function factory($format) {
		if($format == self::FORMAT_JSON) {
			return new QFormat_Json();
		}
		if($format == self::FORMAT_XML) {
			return new QFormat_Xml();
		}
		if($format == self::FORMAT_ARRAY) {
			return new QFormat_Array();
		}

		throw new MQ_Exception('Not support MQ data format：'. $format);
	}

	abstract public function execute($data);
}