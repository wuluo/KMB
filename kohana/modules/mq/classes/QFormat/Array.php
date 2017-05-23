<?php
/**
 * Data format as array
 * @author: panchao
 */
class QFormat_Array extends QFormat {

	/**
	 * execute
	 * @param $data
	 * @return string
	 */
	public function execute($data) {
		return http_build_query($data);
	}
}