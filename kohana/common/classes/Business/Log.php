<?php

class Business_Log extends Business {

	/**
	 * 获得日志列表分页
	 * @param string $keyword
	 * @param number $page
	 * @param number $size
	 * @param string $key
	 */
	public function getPagination($keyword = '', $search = 'message', $page = 1, $size = 1, $key = 'page') {
		if ($keyword) {
			$count = Dao::factory('Log')->countLogByKeyword($keyword, $search);
		} else {
			$count = Dao::factory('Log')->countLog();
		}

		return Pagination::factory()
			->total($count)
			->number($size)
			->key($key)
			->execute();
	}

	public function getLogList($keyword = '', $search = 'message', $number = 0, $offset = 0) {
		if ($keyword) {
			$logs = Dao::factory('Log')->getLogsByKeyword($keyword, $search, $number, $offset);
		} else {
			$logs = Dao::factory('Log')->getLogs($number, $offset);
		}

		return $logs;
	}

}
