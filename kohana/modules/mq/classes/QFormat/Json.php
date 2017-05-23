<?php
/**
 * Data format as json
 * @author: panchao
 */
class QFormat_Json extends QFormat {

	/**
	 * execute
	 * @param $data
	 * @return string
	 */
	public function execute($data) {
		return json_encode($data, true);
	}
}