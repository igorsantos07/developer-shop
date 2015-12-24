<?php
$I = new ApiTester($scenario);
$I->wantTo('Insert items and checkout');

/**
 * @var array $item1
 * @var array $item2
 * @var int   $order
 */
require "_AddItems.php";

$I->amGoingTo('checkout');
$I->sendPATCH('cart');
$I->seeCodeAndJson(200, [
    'id'    => $order,
    'total' => $item1['price'] + $item2['price']
]);

$I->amGoingTo('verify the order was really ~closed~');
$I->sendGET('cart');
$I->assertEquals(sizeof(json_decode($I->grabResponse())->items), 0, 'verify the cart is now empty');