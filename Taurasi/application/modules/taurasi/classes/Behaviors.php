<?php
/**
 * Created by PhpStorm.
 * User: guojianing
 * Date: 2017/3/27
 * Time: 10:29
 * description 推送行为到推荐系统
 */
use RdKafka\Producer;
use RdKafka\Conf;
class Behaviors{

    /**
     * 操作
     */
    const COLLECT_ADD = 1; // 收藏
    const COLLECT_DEL = 0; // 取消收藏
    const PRAISE_ADD = 1; // 赞
    const PRAISE_DEL = 0; // 取消赞
    const SUBSCRIBE_ADD = 1; // 订阅
    const SUBSCRIBE_DEL = 0; // 取消订阅

    /**
     * 队列
     */
    const QUEUE_COLLECT = 'collect';
    const QUEUE_PRAISE = 'praise';
    const QUEUE_SUBSCRIBE = 'subscribe';

    protected static $_instance;

    public static function instance(){
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 推送行为
     * param string queue 队列
     * param string  operate 操作
     * param json data 数据
     */
    public static function send($queue, $data){
        try {
            if (empty($data) || !Utils::isJson($data)) {
                throw new InvalidArgumentException('content type error', 400);
            }
            $config = Kohana::$config->load('behaviors');
            $rkConf = new Conf();
            $rkConf->set('broker.version.fallback', $config['audit']['broker']['version']);
            $rk = new Producer($rkConf);
            $rk->addBrokers($config['audit']['broker']['host']);
            $topic = $rk->newTopic($config['audit']['producer'][$queue]);
            $topic->produce(RD_KAFKA_PARTITION_UA, 0, $data);
            /*return json_encode([
                'code' => 200,
                'msg'  => 'success'
            ]);*/
        } catch (Exception $e) {
            $code = $e->getCode() ? $e->getCode() : 500;
            $msg  = $code == 500 ? 'server error' : $e->getMessage();
            /*return json_encode([
                'code' => $code,
                'msg'  => $msg
            ]);*/
        }


    }

}