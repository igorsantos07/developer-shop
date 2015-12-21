<?php
$devs = \Codeception\Util\Fixtures::get('devs');
$gen_item = function() use ($devs) {
    return [
        'item'  => $devs[array_rand($devs)],
        'price' => rand(10.01, 1000.99),
    ];
};

$I = new ApiTester($scenario);
$I->wantTo('add items to cart');

$item = $gen_item();
$I->sendPUT('cart', $item);
$I->seeCodeAndJson(201, $item);
$order = json_decode($I->grabResponse())->order_id;

$I->amGoingTo('add another item to the same order');
$new_item = $gen_item();
$I->sendPUT('cart', $item);
$I->seeCodeAndJson(201, $item);
$order2 = json_decode($I->grabResponse())->order_id;
$I->assertEquals($order, $order2, 'The two items are in the same order');