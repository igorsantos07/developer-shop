<?php

define('ENV', getenv('ENV')?: 'dev');
define('PROD', ENV == 'prod');

return [
    'database' => PROD?
        call_user_func(function() {
            $url = parse_url(getenv('DATABASE_URL'));
            return [
                'host' => $url['host'],
                'user' => $url['user'],
                'pass' => $url['pass'],
                'name' => substr($url['path'], 1),

            ];
        }) : [
            'host' => 'localhost',
            'user' => 'devshop',
            'pass' => 'devshop',
            'name' => 'devshop',
        ]

];
