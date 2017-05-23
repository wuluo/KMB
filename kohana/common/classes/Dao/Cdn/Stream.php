<?php
/**
 * cdn 流数据访问层
 * @author: panchao
 */
class Dao_Cdn_Stream extends Dao {

	protected $_primaryKey = 'cdn_id';

	protected $_tableName = 'cdn_stream';

	protected $_db = 'system';

	protected $_modelName = 'Model_Cdn_Stream';

	const STATUS_DEFAULT = 1;  //默认有效
	const STATUS_DELETE = 0; //无效

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
	 * 根据 input 获取cdn_stream
	 * @param $input
	 * @return object
	 */
	public function getCdnByInput($input) {
		return DB::select('*')
			->from($this->_tableName)
			->where('status', '=', self::STATUS_DEFAULT)
			->and_where('input', '=', $input)
			->as_object($this->_modelName)
			->execute($this->_db);
	}
}