<?php
class XMLHelper {
	
	static public function arrayToXML($data = array()) {
		static $xml = '';
	
		foreach($data as $key => $item) {
			$xml .= "<$key>";
			if(!is_array($item)) {
				$xml .= $item;
			} else {
				XMLHelper::arrayToXML($item);
			}
			$xml .= "</$key>";
		}
		return "<document>".$xml."</document>";
	}
}