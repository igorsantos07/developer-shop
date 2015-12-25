<?php
$coupon = \Codeception\Util\Fixtures::get('coupon');
$I = new ApiTester($scenario);
$I->wantTo('Use a coupon in my cart');

/**
 * @var array $item1
 * @var array $item2
 * @var int   $order
 */
require "_AddItems.php";

$I->amGoingTo('use an invalid coupon code');
$I->sendPOST('cart/coupon', ['code' => 'XXX']);
$I->seeResponseCodeIs(404);

$I->amGoingTo('use a valid coupon code');
$I->sendPOST('cart/coupon', ['code' => $coupon['code']]);
$I->seeResponseCodeIs(200);
$I->seeResponseEquals($coupon['discount']);
$I->sendGET('cart');
$I->seeResponseContainsJson([
    'total' => ($item1['price'] + $item2['price']) * ((100 - $coupon['discount'])/100)
]);

$I->amGoingTo('remove the coupon');
$I->sendDELETE('cart/coupon');
$I->seeCode(204);

//TODO: we should not test a checkout with the coupon since this would be an integration test (?), and we're already t3esting the order total up there anyway