<?php
use \Codeception\Util\Fixtures;

require dirname(__FILE__).'/../../src/config/constants.php';

Fixtures::add('devs', [
    'igorsantos07', 'brenoc', 'firstdoit'
]);

$faker = Faker\Factory::create();
Fixtures::add('faker', $faker);

Fixtures::add('coupons', [
    ['code' => 'SHIPIT', 'discount' => 0.20],
    ['code' => 'NOTTHIS', 'discount' => 0.01]
]);