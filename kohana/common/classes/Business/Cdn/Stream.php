<?php
/**
 * Cdn 流对应关系业务逻辑
 * @author: panchao
 */
class Business_Cdn_Stream extends Business {

	/**
	 * 根据 input 获取cdn_stream
	 * @param $input
	 */
	public function getCdnByInput($input) {
		return Dao::factory('Cdn_Stream')->getCdnByInput($input);
	}

}