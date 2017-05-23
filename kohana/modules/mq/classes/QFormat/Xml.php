<?php
/**
 * Data format as json
 * @author: panchao
 */
class QFormat_Xml extends QFormat {

	/**
	 * execute
	 * @param array $data
	 * @return mixed
	 */
	public function execute($data) {
		$string = "<?xml version='1.0' encoding='utf-8'?><document>";
		$string = self::createItem($string, $data);
		$string .= "</document>";

		$xml = simplexml_load_string($string);
		return $xml->asXML();
	}

	static public function createItem($string, Array $data) {
		foreach($data as $key => $value) {
			if(is_array($value)) {
				$string .= "<{$key}>";
				$string = self::createItem($string, $value);
				$string .= "</{$key}>";
			} else {
				$string .= "<{$key}>{$value}</{$key}>";
			}
		}
		return $string;
	}
}