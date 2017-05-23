<?php
defined('SYSPATH') or die('No direct script access.');
require SYSPATH . 'classes/Kohana/Core' . EXT;

if (is_file(APPPATH . 'classes/Kohana' . EXT)) {
    require APPPATH . 'classes/Kohana' . EXT;
} else {
    require SYSPATH . 'classes/Kohana' . EXT;
}

date_default_timezone_set('Asia/Shanghai');
setlocale(LC_ALL, 'zh_CN.utf-8');
spl_autoload_register(['Kohana', 'auto_load']);

//spl_autoload_register(array('Kohana', 'auto_load_lowercase'));

ini_set('unserialize_callback_func', 'spl_autoload_call');

mb_substitute_character('none');
// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang('utf-8');

if (isset($_SERVER['SERVER_PROTOCOL'])) {
    // Replace the default protocol.
    HTTP::$protocol = $_SERVER['SERVER_PROTOCOL'];
}

/*
 * 环境：PRODUCTION、STAGING、DEVELOPMENT（默认）
 */
/**
 * Initialize Kohana, setting the default options.
 * The following options are available:
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - integer  cache_life  lifetime, in seconds, of items cached              60
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 * - boolean  expose      set the X-Powered-By header                        FALSE
 */
Kohana::init([
    'base_url'   => MODULEDIR,
    'index_file' => false,
    'cache_dir'  => CACHEDIR,
    'errors'     => false,
]);

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
//根据服务器真实路径配置
Kohana::$log->attach(new Log_File(LOGDIR));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);

/**
 * 根据不同的环境选择不同的配置文件路径
 */
Kohana::$config->attach(new Config_File('config/'));

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules([
    // 'auth'       => MODPATH.'auth',       // Basic authentication
	'cache'      => MODPATH.'cache',      // Caching with multiple backends
    // 'codebench'  => MODPATH.'codebench',  // Benchmarking tool
    'database' => MODPATH . 'database', // Database access
    'image'    => MODPATH . 'image', // Image manipulation
    // 'minion'     => MODPATH.'minion',     // CLI Tasks
    // 'orm'        => MODPATH.'orm',        // Object Relationship Mapping
    // 'unittest'   => MODPATH.'unittest',   // Unit testing
    // 'userguide'  => MODPATH.'userguide',  // User guide and API documentation
	'author' => MODPATH . 'author',
    'redisd'   => MODPATH . 'redisd',
    'curl'     => MODPATH . 'curl',
    'misc'     => MODPATH . 'misc',
    'logger'   => MODPATH . 'logger',
    //    'sphinx' => MODPATH . 'sphinx',
    'qrcode'   => MODPATH . 'qrcode',
    'agent'    => MODPATH . 'agent',
    'taurasi'       => APPMODPATH . 'taurasi',
]);

Kohana::$log->attach(new Log_Database());

Cookie::$salt = 'GQdWc7%g5aE2ME0hl2&p5Ex*FB$QqBDR';
Session::$default = 'database'; //memcache,redis可选

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */
Route::set('default', '(<controller>(/<action>))')->defaults([
        'controller' => 'Index',
        'action'     => 'index',
    ]);
Route::set('api', 'api/<controller>(/<action>)')->defaults([
        'directory'  => 'api',
        'controller' => 'main',
        'action'     => 'index',
    ]);


