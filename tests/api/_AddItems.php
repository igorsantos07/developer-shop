<?php
$devs = \Codeception\Util\Fixtures::get('devs');
$gen_item = function() use ($devs) {
    return [
        'item'  => $devs[array_rand($devs)],
        'price' => floatify(rand(10.01, 1000.99)),
    ];
};

/* ********************************************************** */

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
