<?php
use Codeception\Util\Fixtures;
/** @var Faker\Generator $faker */
$faker   = Fixtures::get('faker');
$coupons = Fixtures::get('coupons');

$I       = new ApiTester($scenario);
$I->wantTo('Use a coupon in my cart');

/**
 * @var array $item1
 * @var array $item2
 * @var int   $order
 */
require "_AddItems.php";

$I->amGoingTo('use an invalid coupon code');
$I->sendPOST('cart/coupon', ['code' => 'XXX']);
$I->seeResponseCodeIs(HTTP_NOT_FOUND);

$I->amGoingTo('use a valid coupon code');
$I->sendPOST('cart/coupon', ['code' => $coupons[0]['code']]);
$I->seeResponseCodeIs(HTTP_OK);
$I->seeResponseEquals($coupons[0]['discount']);
$I->sendGET('cart');
$I->seeResponseContainsJson([
    'total' => ($item1['price'] + $item2['price']) * (1 - $coupons[0]['discount'])
]);

$I->amGoingTo('remove the wrong coupon');
$I->sendDELETE('cart/coupon?code='.$coupons[1]['code']);
$I->seeResponseCodeIs(HTTP_CONFLICT);

$I->amGoingTo('remove a weird coupon');
$I->sendDELETE('cart/coupon?code='.$faker->lexify('??????????'));
$I->seeResponseCodeIs(HTTP_NOT_FOUND);

$I->amGoingTo('remove the right coupon');
$I->sendDELETE('cart/coupon?code='.$coupons[0]['code']);
$I->seeResponseCodeIs(HTTP_NO_CONTENT);

$I->amGoingTo('remove the right coupon again');
$I->sendDELETE('cart/coupon?code='.$coupons[0]['code']);
$I->seeResponseCodeIs(HTTP_EXPECTATION_FAILED);

//TODO: we should not test a checkout with the coupon since this would be an integration test (?), and we're already testing the order total up there anyway