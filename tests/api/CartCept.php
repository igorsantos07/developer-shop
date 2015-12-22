<?php
$I = new ApiTester($scenario);
$I->wantTo('Test the cart behaviour with items');

/* ********************************************************** */
$devs = \Codeception\Util\Fixtures::get('devs');
$gen_item = function() use ($devs) {
    return [
        'item'  => $devs[array_rand($devs)],
        'price' => rand(10.01, 1000.99),
    ];
};
/* ********************************************************** */

$I->amGoingTo('add items to cart');
$I->sendGET('cart');
$I->seeCodeAndJson(200, ['items' => [], 'total' => 0]);

$I->amGoingTo('add items to cart');
$item1 = $gen_item();
$I->sendPUT('cart', $item1);
$I->seeCodeAndJson(201, $item1);
$data    = json_decode($I->grabResponse());
$order   = $data->order_id;
$item_id = $data->id;

$I->amGoingTo('add another item to the same order');
$item2 = $gen_item();
$I->sendPUT('cart', $item2);
$I->seeCodeAndJson(201, $item2);
$I->assertEquals($order, json_decode($I->grabResponse())->order_id, 'The two items are in the same order');

$I->amGoingTo('Verify order');
$I->sendGET('cart');
$I->expectTo('see items in order');
$I->seeCodeAndJson(200, ['items' => [$item1, $item2]]);
$I->expectTo('see correct total');
$I->seeResponseContainsJson(['total' => $item1['price'] + $item2['price']]);

$I->amGoingTo('delete an item');
$I->sendDELETE('cart/item/'.$item_id);
$I->seeResponseCodeIs(204);
$I->seeResponseEquals('');
$I->sendGET('cart');
$I->seeCodeAndJson(200, ['items' => [$item2], 'total' => $item2['price']]);

$I->amGoingTo('clear the cart');
$I->sendDELETE('cart');
$I->seeResponseCodeIs(204);
$I->seeResponseEquals('');
$I->sendGET('cart');
$I->seeCodeAndJson(200, ['items' => [], 'total' => 0]);