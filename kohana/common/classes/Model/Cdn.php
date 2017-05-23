<?php
/**
 * cdn 模型
 * @author: panchao
 */
class Model_Cdn extends Model_BaseModel {

	const IS_DELETE_TRUE = 1;  //删除 是
	const IS_DELETE_FALSE = 0; //删除 否

	const IS_DEFAULT_TRUE = 1; //默认 是
	const IS_DEFAULT_FALSE = 0; //默认 否

	/**
	 * @return string
	 */
	public function getIsDefaultText() {
		if($this->is_default == self::IS_DEFAULT_TRUE) {
			return '<p class="text-success">是</p>';
		}else {
			return '<p class="text-info">否</p>';
		}
	}
}