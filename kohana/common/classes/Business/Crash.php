<?php
class Business_Crash extends Business {

	/**
	 * 获取一组异常日志
	 * @param integer $level
	 * @param integer $offset
	 * @param integer $number
	 * @return array
	 */
	public function getLogCrashes($number = 0, $offset = 0) {
		if(!is_numeric($number) || !is_numeric($offset)) {
			return array();
		}
		return Dao::factory('Crash')->getLogCrashes($number, $offset);
	}

	/**
	 * 根据关键字获取一组异常日志
	 * @param string $keyword
	 * @param integer $offset
	 * @param integer $number
	 * @return array
	 */
	public function getLogCrashesByKeyword($keyword, $number = 0, $offset = 0) {
		if(!is_numeric($number) || !is_numeric($offset)) {
			return array();
		}
		return Dao::factory('Crash')->getLogCrashesByKeyword($keyword, $number, $offset);
	}

	/**
	 * 获取一组异常日志的数量
	 * @return integer
	 */
	public function countLogCrash() {
		return Dao::factory('Crash')->countLogCrash();
	}

	/**
	 * 根据关键字获取一组异常日志的数量
	 * @param string $keyword
	 * @return integer
	 */
	public function countLogCrashByKeyword($keyword = '') {
		return Dao::factory('Crash')->countLogCrashByKeyword($keyword);
	}
	
	/**
	 * 获得crash日志列表分页
	 * @param string $keyword
	 * @param number $page
	 * @param number $size
	 * @param string $key
	 */
	public function getCrashPagination($keyword = '', $page = 1, $size = 1, $key = 'page') {
		if($keyword) {
			$count = Dao::factory('Crash')->countLogCrashByKeyword($keyword);
		} else {
			$count = Dao::factory('Crash')->countLogCrash();
		}
	
		return Pagination::factory()
			->total($count)
			->number($size)
			->key($key)
			->execute();
	}
	
	/**
	 * 获取crash日志单页数据
	 * @param string keyword
	 * @param number number
	 * @param number offset
	 */
	public function getCrashesList($keyword = '', $number = 0, $offset = 0) {
		if($keyword) {
			$crashes = Dao::factory('Crash')->getLogCrashesByKeyword($keyword, $number, $offset);
		} else {
			$crashes = Dao::factory('Crash')->getLogCrashes($number, $offset);
		}

		return $crashes;
	}
	
	public function deleteBefore($start,$end){
		return Dao::factory("Crash")->deleteBefore($start,$end);
	}
}
