<?php
error_reporting(E_ALL | E_STRICT);
//public目录
define('DOCROOT', __DIR__ . '/');
//project目录
define('BASEPATH', realpath(__DIR__ . '/../'));
//kohana和project所在目录
define('ROOTPATH', realpath(BASEPATH . '/../'));
define('EXT', '.php');
define('APPPATH', BASEPATH . '/application/');
define('MODPATH', ROOTPATH . '/kohana/modules/');
define('APPMODPATH', APPPATH . 'modules/');
define('SYSPATH', ROOTPATH . '/kohana/system/');
define('MODULEDIR', '/');
define('PORTAL', basename(__FILE__));
define('CACHEDIR', APPPATH . 'cache/');
define('LOGDIR', APPPATH . 'logs/');
if (!defined('KOHANA_START_TIME')) {
	define('KOHANA_START_TIME', microtime(TRUE));
}
if (!defined('KOHANA_START_MEMORY')) {
	define('KOHANA_START_MEMORY', memory_get_usage());
}
require APPPATH . 'bootstrap' . EXT;
echo Request::factory(TRUE, [], FALSE)
	->execute()
	->send_headers(TRUE)
	->body();
