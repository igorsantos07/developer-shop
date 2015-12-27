<?php
use Codeception\Util\Fixtures;
/** @var Faker\Generator $faker */
$faker = Fixtures::get('faker');
$orgs  = Fixtures::get('orgs');

$I = new ApiTester($scenario);
$I->wantTo('list of developers of an organization');

$I->amGoingTo('Get a non-existent organization');
$I->sendGET('dev/organization/'.$faker->lexify('??????????'));
$I->seeResponseCodeIs(404);

$I->amGoingTo('Get an existent organization');
$I->sendGET('dev/organization/'.$orgs[array_rand($orgs)]);
$I->seeResponseCodeIs(200);
$org = json_decode($I->grabResponse(), true);
$I->assertTrue(is_numeric($org['size']), 'rate is numeric');
$I->assertTrue(is_array($org['members']), 'has list of members');
$I->assertGreaterThanOrEqual(sizeof($org['members']), 1, 'organization has at least one member');
$I->assertTrue(is_string($org['members'][0]['login']), 'first member has "username"');