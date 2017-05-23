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
			'dsn' => 'mysql:host=bj05-ops-mys03.dev.gomeplus.com;port=3306;dbname=video_api;charset=utf8',
			'username' => 'tester',
			'password' => 'Test_usEr',
			'persistent' => FALSE,
		),
		'table_prefix' => 'api_',
		'charset' => 'utf8',
		'caching' => FALSE,
	),
);