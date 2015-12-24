<?php
use Codeception\Util\Fixtures;
/** @var Faker\Generator $faker */
$faker = Fixtures::get('faker');
$devs  = Fixtures::get('devs');

$I = new ApiTester($scenario);
$I->wantTo('get the user price based on the GitHub API');

$I->amGoingTo('Get a non-existent user');
$I->sendGET('dev/'.$faker->lexify('??????????'));
$I->seeResponseCodeIs(404);

$I->amGoingTo('Get an existent user');
$I->sendGET('dev/'.$devs[array_rand($devs)]);
$I->seeResponseCodeIs(200);
$dev = json_decode($I->grabResponse());
$I->assertTrue(is_numeric($dev->price), 'price is numeric');
$I->assertTrue(is_string($dev->username) && strlen($dev->username) != 0, 'username is present and is string');