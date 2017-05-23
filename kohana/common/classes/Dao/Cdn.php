<?php
/**
 * cdn 数据访问层
 * @author: panchao
 */
class Dao_Cdn extends Dao {

	protected $_primaryKey = 'cdn_id';

	protected $_tableName = 'cdn';

	protected $_db = 'system';

	protected $_modelName = 'Model_Cdn';

	const IS_DELETE_TRUE = 1;  //删除 是
	const IS_DELETE_FALSE = 0; //删除 否

	const IS_DEFAULT_TRUE = 1; //默认 是
	const IS_DEFAULT_FALSE = 0; //默认 否

	/**
	 * 插入一行
	 * @param array $values
	 * @return array
	 */
	public function insert(array $values) {
		return DB::insert($this->_tableName)
			->columns(array_keys($values))
			->values(array_values($values))
			->execute($this->_db);
	}

	/**
	 * 根据 cdn_name 查找cdn
	 * @param $cdnName
	 * @return object
	 */
	public function getCdnByCdnName($cdnName) {
		return DB::select('*')
			->from($this->_tableName)
			->where('is_delete', '=', self::IS_DEFAULT_FALSE)
			->and_where('cdn_name', '=', $cdnName)
			->as_object($this->_modelName)
			->execute($this->_db);
	}

	/**
	 * 根据 cdn_id 查找cdn
	 * @param $cdnId
	 * @return object
	 */
	public function getCdnByCdnId($cdnId) {
		return DB::select('*')
			->from($this->_tableName)
			->where('is_delete', '=', self::IS_DEFAULT_FALSE)
			->and_where($this->_primaryKey, '=', $cdnId)
			->as_object($this->_modelName)
			->execute($this->_db);
	}

	/**
	 * 根据关键字查找cdn数量
	 * @param $keywords
	 */
	public function countCdnsByKeywords($keywords) {
		$select = DB::select(DB::expr('count(*) AS recordCount'))
			->from($this->_tableName)
			->where('is_delete', '=', self::IS_DELETE_FALSE);
		if($keywords) {
			$select->and_where('cdn_name', '=', $keywords);
		}
		return $select->execute($this->_db)
			->get('recordCount');
	}

	/**
	 * 根据关键字分页获取cdn
	 * @param $keywords
	 * @param $offset
	 * @param $number
	 * @return object
	 */
	public function getCdnsByKeywordsAndLimit($keywords, $offset, $number) {
		$select = DB::select('*')
			->from($this->_tableName)
			->where('is_delete', '=', self::IS_DELETE_FALSE)
			->order_by($this->_primaryKey, 'DESC');
		if($keywords) {
			$select->and_where('cdn_name', '=', $keywords);
		}
		if($offset) {
			$select->offset($offset);
		}
		if($number) {
			$select->limit($number);
		}

		return $select->as_object($this->_modelName)
			->execute($this->_db);
	}

	/**
	 * 获取 cdn 总数
	 */
	public function countCdns() {
		$select = DB::select(DB::expr('count(*) AS recordCount'))
			->from($this->_tableName)
			->where('is_delete', '=', self::IS_DELETE_FALSE);

		return $select->execute($this->_db)
			->get('recordCount');
	}

	/**
	 * 分页获取 cdn
	 * @param $offset
	 * @param $number
	 * @return object
	 */
	public function getCdnsByLimit($offset, $number) {
		$select = DB::select('*')
			->from($this->_tableName)
			->where('is_delete', '=', self::IS_DELETE_FALSE)
			->order_by($this->_primaryKey, 'DESC');
		if($offset) {
			$select->offset($offset);
		}
		if($number) {
			$select->limit($number);
		}

		return $select->as_object($this->_modelName)
			->execute($this->_db);
	}

	/**
	 * 根据 cdn_id 修改 cdn
	 * @param $values
	 * @param $cdnId
	 * @return object
	 */
	public function updateByCdnId($values, $cdnId) {
		return DB::update($this->_tableName)
			->set($values)
			->where($this->_primaryKey, '=', $cdnId)
			->execute($this->_db);
	}

	/**
	 * 根据 rtmp_push 获取 cdn
	 * @param $rtmpPush
	 * @return object
	 */
	public function getCdnByRtmpPush($rtmpPush) {
		return DB::select('*')
			->from($this->_tableName)
			->where('is_delete', '=', self::IS_DEFAULT_FALSE)
			->and_where('rtmp_push', '=', $rtmpPush)
			->as_object($this->_modelName)
			->execute($this->_db);
	}

	/**
	 * 根据 is_default 获取 cdn
	 * @param $isDefault
	 * @return object
	 */
	public function getCdnByIsDefault($isDefault) {
		return DB::select('*')
			->from($this->_tableName)
			->where('is_delete', '=', self::IS_DEFAULT_FALSE)
			->and_where('is_default', '=', $isDefault)
			->as_object($this->_modelName)
			->execute($this->_db);
	}

	/**
	 * 根据多个 cdn_id 来查找 cdn
	 * @param array $cdnIds
	 * @return object
	 */
	public function getCdnByCdnIds($cdnIds = []) {
		return DB::select('*')
			->from($this->_tableName)
			->where('is_delete', '=', self::IS_DEFAULT_FALSE)
			->and_where($this->_primaryKey, 'IN', $cdnIds)
			->as_object($this->_modelName)
			->execute($this->_db);
	}
}