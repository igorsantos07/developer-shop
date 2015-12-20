<?php
$config = require('src/config.php');
$db     = $config['database'];
$port   = isset($db['port'])? ':'.$db['port'] : '';


return [
    'paths' => [
        'migrations' => __DIR__.'/migrations',
    ],

    'environments' => [
        'default_database' => 'main',
        'main' => [
            'name' => $db['name'],
            'connection' => new \PDO("pgsql:dbname={$db['name']};host={$db['host']}{$port}", $db['user'], $db['pass'])
        ]
    ]
];