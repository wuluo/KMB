<?php
/**
 * 频道数据访问
 * @author: panchao
 */
class Dao_Channel extends Dao {

	protected $_primaryKey = 'channel_id';

	protected $_tableName = 'channel';

	protected $_db = 'system';

	protected $_modelName = 'Model_Channel';

	const IS_DELETE_TRUE = 1;  //删除
	const IS_DELETE_FALSE = 0; //正常

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
	 * 根据 channel_id 获取 channel
	 * @param $channelId
	 * @return object
	 */
	public function getChannelByChannelId($channelId) {
		return DB::select('*')
			->from($this->_tableName)
			->where($this->_primaryKey, '=', $channelId)
			->and_where('is_delete', '=', self::IS_DELETE_FALSE)
			->as_object($this->_modelName)
			->execute($this->_db);
	}

	/**
	 * 根据 push_stream_id 获取channel
	 * @param $pushStreamId
	 * @return object
	 */
	public function getChannelByPushStreamId($pushStreamId) {
		return DB::select('*')
			->from($this->_tableName)
			->where('push_stream_id', '=', $pushStreamId)
			->and_where('is_delete', '=', self::IS_DELETE_FALSE)
			->as_object($this->_modelName)
			->execute($this->_db);
	}

	/**
	 * 获取整个channel数据
	 * @param array $ids 查找id
	 * @return object
	 */
	public function getChannels($ids = array()) {
		$channel = DB::select('*')
			->from($this->_tableName);
			if (!empty($ids)) {
				$channel->where('channel_id', 'IN',$ids);
			}
		return $channel->as_object($this->_modelName)
			->execute($this->_db);
	}
	/**
	 * 获取某些ip的使用量
	 * @param  string $field   查找字段
	 * @param  string $ip      ip
	 * @param  string $groupBy 分组字段
	 * @return object
	 */
	public function getConvertIpSum($field, $ip, $groupBy) {
		return DB::select($field)
			->distinct(TRUE)
			->from($this->_tableName)
			->where('encode_ip', '=', $ip)
			->group_by($groupBy)
			->as_object($this->_modelName)
			->execute($this->_db);
	}
	/**
	 * 增加channel数据
	 * @param  array $flow 插入数据
	 * @return array
	 */
	public function insertChannel($flow) {
		$columns = array_keys($flow[0]);
		$insert = DB::insert($this->_tableName)
			->columns($columns);
			foreach($flow as $value) {
				$insert->values(array_values($value));
			}
		return $insert->execute($this->_db);
	}
	/**
	 * 删除channel
	 * @param  array $ids channelid
	 * @return mixd
	 */
	public function deleteChannel($ids) {
		return DB::delete($this->_tableName)
			->where('channel_id', 'IN', $ids);	
	}
	/**
	 * 修改channel信息
	 * @param  array $data  修改对象
	 * @param  array $where 条件
	 * @return 影响行数
	 */
	public function updateChannel($data, $where) {
		return DB::update($this->_tableName)
			->set($data)
			->where('channel_id', 'IN', $where)
			->execute($this->_db);
	}
	/**
	 * 获取播放channel
	 * @param  string $stream 播放token
	 * @return object
	 */
	public function getChannelByPlayStreamId($stream) {
		return DB::select('*')
			->from($this->_tableName)
			->where('play_stream_id', '=', $stream)
			->and_where('is_delete', '=', self::IS_DELETE_FALSE)
			->as_object($this->_modelName)
			->execute($this->_db);
	}
	/**
	 * 获取最高清晰度channel
	 * @param  array $channelIds channelID
	 * @return object
	 */
	public function getMustClarityChannels($channelIds) {
		$channel = DB::select('*')
			->from($this->_tableName);
			if (!empty($channelIds)) {
				$channel->where('channel_id', 'IN',$channelIds);
			}
		$channel->and_where('is_delete', '=', self::IS_DELETE_FALSE)
			->order_by('clarity','DESC')
			->limit(1);
		return $channel->as_object($this->_modelName)
			->execute($this->_db);
	}
}