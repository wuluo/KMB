<?php defined('SYSPATH') OR die('No direct script access.');

return array(

	'cookie' => array(
		'encrypted' => FALSE,
	),

	'redis' => array(
		'encrypted' => FALSE,
		'server' => '127.0.0.1',
		'port' => '6379',
		'timeout' => 30,
		'persistent' => FALSE,
		'password' => 'foobared',
	),
	'memcache' => array(
			'encrypted' => FALSE,
			'server' => '127.0.0.1',
			'port' => '11211',
			'timeout' => 30,
			'persistent' => FALSE,
			'password' => '',
	),
);
