<?php
$I = new ApiTester($scenario);
$I->wantTo('Test the cart behaviour with items');

$I->amGoingTo('confirm the cart is empty');
$I->sendGET('cart');
$I->seeCodeAndJson(200, ['items' => [], 'total' => floatify(0)]);

/**
 * @var array $item1
 * @var array $item2
 * @var int   $item_id
 */
require "_AddItems.php";

$I->amGoingTo('Verify order');
$I->sendGET('cart');
$I->expectTo('see items in order');
$I->seeCodeAndJson(200, ['items' => [$item1, $item2]]);
$I->expectTo('see correct total');
$I->seeResponseContainsJson(['total' => floatify($item1['final_price'] + $item2['final_price'])]);

$I->amGoingTo('delete an item');
$I->sendDELETE('cart/item/'.$item_id);
$I->seeResponseCodeIs(204);
$I->seeResponseEquals('');
$I->sendGET('cart');
$I->seeCodeAndJson(200, ['items' => [$item2], 'total' => floatify($item2['final_price'])]);

$I->amGoingTo('clear the cart');
$I->sendDELETE('cart');
$I->seeResponseCodeIs(204);
$I->seeResponseEquals('');
$I->sendGET('cart');
$I->seeCodeAndJson(200, ['items' => [], 'total' => floatify(0)]);