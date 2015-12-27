<?php
use Codeception\Util\Fixtures;
/** @var Faker\Generator $faker */
$faker   = Fixtures::get('faker');
$orgs    = Fixtures::get('orgs');
$sm_orgs = Fixtures::get('small_orgs');

$I = new ApiTester($scenario);
$I->wantTo('list of developers of an organization');

$I->amGoingTo('Get a non-existent organization');
$I->sendGET('dev/organization/'.$faker->lexify('??????????'));
$I->seeResponseCodeIs(404);

$I->amGoingTo('Get an existent organization on different information levels');
$get_org = function($level, $orgs) use ($I) {
    $I->amGoingTo("Request Org $level information");
    $query = $level? '?level='.$level : '';
    $I->sendGET('dev/organization/'.$orgs[array_rand($orgs)].$query);
    $I->seeResponseCodeIs(200);
    $org = json_decode($I->grabResponse(), true);
    $I->assertTrue(is_numeric($org['size']), 'has total of members');
    $I->assertTrue(is_array($org['members']), 'has list of members');
    $I->assertGreaterThanOrEqual(1, $org['size'], 'organization has at least one member');
    $I->assertEquals(sizeof($org['members']), $org['size'], 'total is correctly calculated');
    $I->assertTrue(is_string($org['members'][0]['username']), 'first member has "username"');
    return $org;
};

$org = $get_org('basic', $orgs);
$I->assertEmpty($org['members'][0]['name']);
$I->assertEmpty($org['members'][0]['repos']);
$I->assertEmpty($org['members'][0]['rate']);

$org = $get_org(null, $orgs);
$I->assertTrue(is_string($org['members'][0]['name']));
$I->assertTrue(is_numeric($org['members'][0]['repos']));
$I->assertEmpty($org['members'][0]['rate']);

$org = $get_org('user', $orgs);
$I->assertTrue(is_string($org['members'][0]['name']));
$I->assertTrue(is_numeric($org['members'][0]['repos']));
$I->assertEmpty($org['members'][0]['rate']);

$org = $get_org('complete', $sm_orgs); //using only small orgs ensures we won't timeout from taking forever at GitHub
$I->assertTrue(is_string($org['members'][0]['name']));
$I->assertTrue(is_numeric($org['members'][0]['repos']));
$I->assertTrue(is_numeric($org['members'][0]['rate']));
$I->assertTrue(is_array($org['members'][0]['rateDetails']));
