<?php
$root = dirname(__FILE__).'/../../../..';

return [

    /*
    |--------------------------------------------------------------------------
    | Codeception Configurations
    |--------------------------------------------------------------------------
    |
    | This is where you add your Codeception configurations.
    |
    | Webception allows you to have access test suites for multiple applications.
    |
    | Place them in the order you want and they'll appear in the drop-down list
    | in the front-end. The first site in the list will become the default
    | site that's loaded on session load.
    |
    | Just add the site name and full path to the 'codeception.yml' below and you're set.
    |
    */

    'sites' => [
        'DevShop' => $root.'/codeception.yml',
    ],

    /*
    |--------------------------------------------------------------------------
    | Codeception Executable
    |--------------------------------------------------------------------------
    |
    | Codeception is installed as a dependancy of Webception via Composer.
    |
    | You might need to set 'sudo chmod a+x vendor/bin/codecept' to allow Apache
    | to execute the Codeception executable.
    |
    */

    'executable' => $root.'/vendor/bin/codecept',

    /*
    |--------------------------------------------------------------------------
    | You get to decide which type of tests get included.
    |--------------------------------------------------------------------------
    */

    'tests' => ['api' => true],

    /*
    |--------------------------------------------------------------------------
    | When we scan for the tests, we need to ignore the following files.
    |--------------------------------------------------------------------------
    */

    'ignore' => [
        'WebGuy.php',
        'TestGuy.php',
        'CodeGuy.php',
        '_bootstrap.php',
    ],

    /*
    |--------------------------------------------------------------------------
    | Setting the location as the current file helps with offering information
    | about where this configuration file sits on the server.
    |--------------------------------------------------------------------------
    */

    'location' => __FILE__,
];
