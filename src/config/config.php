<?php
require 'constants.php';

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
        ],

    //important to keep an array of arrays, since we might have multiple memcache servers
    'memcached' => PROD?
        [['cache-aws-us-east-1.iron.io', 11211]] :
        [['localhost', 11211]]
];
