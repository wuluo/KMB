<?php
/**
 * desc
 * @package default
 * @author  qichangchun<qichangchun@gomeplus.com>
 * @time:   10:46
 */
return array(
	'api' => array(
		'type' => 'redis',
		'redis' => array(
			'host' => '127.0.0.1',
			'port' => '6379',
			'persistent' => FALSE,
			'password' => NULL,
			'timeout' => 3000,
		),
		'cluster' => array()
	),
);