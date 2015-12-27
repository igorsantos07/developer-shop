<?php
use Codeception\Util\Fixtures;
/** @var Faker\Generator $faker */
$faker   = Fixtures::get('faker');
$coupons = Fixtures::get('coupons');

$I       = new ApiTester($scenario);
$I->wantTo('Use a coupon in my cart');

/**
 * @var array    $item1
 * @var array    $item2
 * @var int      $order
 * @var callable $gen_item
 */
require "_AddItems.php";

$calculate_discount = function($discount, ...$items) {
    $mult = 1 - $discount;
    return array_reduce($items, function($total, $item) use ($mult) {
        return floatify($total + floatify($item['final_price'] * $mult));
    }, 0);
};

$I->amGoingTo('use an invalid coupon code');
$I->sendPOST('cart/coupon', ['code' => 'XXX']);
$I->seeResponseCodeIs(HTTP_NOT_FOUND);

$set_coupon = function($coupon) use ($I, $item1, $item2, $calculate_discount) {
    $I->sendPOST('cart/coupon', ['code' => $coupon['code']]);
    $I->seeCodeAndJson(HTTP_OK, $coupon);
    $I->sendGET('cart');
    $I->seeResponseContainsJson(['total' => $calculate_discount($coupon['discount'], $item1, $item2)]);
};
$I->amGoingTo('use a valid coupon code');
$set_coupon($coupons[0]);

$I->amGoingTo('change the coupon');
$set_coupon($coupons[1]);

$I->amGoingTo('add a new item to see the price discount');
$item3 = $gen_item();
$I->sendPUT('cart', $item3);
$item3['final_price'] = $item3['price'] * $item3['qty'];
$I->sendGET('cart');
$I->seeResponseContainsJson(['total' => $calculate_discount($coupons[1]['discount'], $item1, $item2, $item3)]);

$I->amGoingTo('remove the wrong coupon');
$I->sendDELETE('cart/coupon?code='.$coupons[0]['code']);
$I->seeResponseCodeIs(HTTP_CONFLICT);

$I->amGoingTo('remove a weird coupon');
$I->sendDELETE('cart/coupon?code='.$faker->lexify('??????????'));
$I->seeResponseCodeIs(HTTP_NOT_FOUND);

$I->amGoingTo('remove the right coupon');
$I->sendDELETE('cart/coupon?code='.$coupons[1]['code']);
$I->seeResponseCodeIs(HTTP_NO_CONTENT);

$I->amGoingTo('remove the right coupon again');
$I->sendDELETE('cart/coupon?code='.$coupons[1]['code']);
$I->seeResponseCodeIs(HTTP_EXPECTATION_FAILED);

$I->amGoingTo('verify order value without discounts');
$I->sendGET('cart');
$I->seeResponseContainsJson(['total' => $calculate_discount(0, $item1, $item2, $item3)]);

//we should not test a checkout with the coupon since this would be an integration test (?), and we're already testing the order total up there anyway