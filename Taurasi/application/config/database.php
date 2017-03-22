<?php

return [
    'default' => [
        'type'       => 'PDO',
        'connection' => [
            'dsn'        => 'mysql:host=127.0.0.1;port=3306;dbname=taurasi;charset=utf8',
            'username'   => 'root',
            'password'   => 'root',
            'persistent' => FALSE,
        ],
        'table_prefix' => 'ts_',
        'charset'      => 'utf8',
        'caching'      => FALSE,
    ],
    'account' => array
    (
        'type'       => 'PDO',
        'connection' => array(
                'dsn'        => 'mysql:host=127.0.0.1;port=3306;dbname=taurasi;charset=utf8',
                'username'   => 'root',
                'password'   => 'root',
                'persistent' => FALSE,
        ),
        'table_prefix' => 'ts_',
        'charset'      => 'utf8',
        'caching'      => FALSE,
    ),
];





