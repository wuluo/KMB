<?php

return [
    'custom' => [
        'type'       => 'PDO',
        'connection' => array(
                'dsn'        => 'mysql:host=bj05-ops-mys03.dev.gomeplus.com;port=3306;dbname=video_website;charset=utf8',
                'username'   => 'tester',
                'password'   => 'Test_usEr',
                'persistent' => FALSE,
        ),
        'table_prefix' => '',
        'charset'      => 'utf8',
        'caching'      => FALSE,
        'table'=>'website_log_crash'
    ],
];


