<?php
use \Codeception\Util\Fixtures;

Fixtures::add('devs', [
    'igorsantos07', 'brenoc', 'firstdoit'
]);

$faker = Faker\Factory::create();
Fixtures::add('faker', $faker);
