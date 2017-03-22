<?php

class Dao_Demo extends Dao
{
    protected $_db          = 'default';
    protected $_tableName   = 'ucenter_member';
    protected $_modelName   = 'Model_Demo'; //需要配合 as_object()，讲结果映射成对象
    
    public function getUsers() {
        return DB::select('*')
                 ->from($this->_tableName)
                 ->as_object($this->_modelName)
                 ->execute();
    }
}
