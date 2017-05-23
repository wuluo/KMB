<?php
/**
 * desc
 * @package default
 * @author  qichangchun<qichangchun@gomeplus.com>
 * @date:   2017/2/7
 * @time:   10:24
 */
return array(
	'api' => array
                (
                'type' => 'PDO',
                'connection' => array(
                        'dsn' => 'mysql:host=g1mysmv1.video.pro.gomeplus.com;port=3306;dbname=video_api;charset=utf8',
                        'username' => 'video_user',
                        'password' => 'FcU5mrdt',
                        'persistent' => FALSE,
                ),
                'table_prefix' => 'api_',
                'charset' => 'utf8',
                'caching' => FALSE,
        ),
);