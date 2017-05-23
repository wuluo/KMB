<?php

/**
 * desc
 * @package default
 * @author  qichangchun<qichangchun@gomeplus.com>
 * @date:   2016/6/24
 * @time:   11:18
 */
class Model_Machine_Machine extends Model {

    const STATUS_AVAILABLE_ON = 1;
    const STATUS_AVAILABLE_OFF = 0;
    const MACHINE_HAS_DELETED = -1;

    public function getServiceStr() {
        $services = Kohana::$config->load("machine.service");
        if (is_array($services) && !empty($services)) {
            return $services[$this->service];
        }
        return false;
    }

    public function getGroupStr() {
        $groups = Kohana::$config->load("machine.group");
        if (is_array($groups) && !empty($groups)) {
            return $groups[$this->service][$this->machine_group];
        }
        return false;
    }

    public function getStatus() {
        $statusStr = "";
        switch ($this->status) {
            case self::MACHINE_AVALIABLE:
                $statusStr = '<span class="label label-success">正常</span>';
                break;
            case self::MACHINE_NOT_AVALIABLE:
                $statusStr = '<span class="label label-warning">删除</span>';
                break;
        }
        return $statusStr;
    }

    /**
     * 是否可用
     */
    public function getAvailable() {
        switch ($this->is_available) {
            case self::STATUS_AVAILABLE_ON:
                $mesg = '<span class="label label-success">是</span>';
                break;
            case self::STATUS_AVAILABLE_OFF:
                $mesg = '<span class="label label-warning">否</span>';
                break;
            default:
                $mesg = '<span class="label label-danger">未知</span>';
                break;
        }
        return $mesg;
    }

    public function getCreateTime($format = 'Y-m-d H:i') {
        return !empty($this->create_time) ? date($format, $this->create_time): '';
    }

    public function getUpdateTime($format = 'Y-m-d H:i') {
        return $this->update_time != 0 ? date($format, $this->update_time): '';
    }

}