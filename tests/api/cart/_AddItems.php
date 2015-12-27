<?php
$devs = \Codeception\Util\Fixtures::get('devs');
$gen_item = function() use ($devs) {
    return [
        'item'  => $devs[array_rand($devs)],
        'price' => floatify(rand(1000, 99999)/100),
        'qty'   => floatify(rand(10, 200)/10)
    ];
};

$add_item = function() use ($gen_item, $I) {
    $item = $gen_item();
    $I->sendPUT('cart', $item);
    $item += ['final_price' => floatify($item['price'] * $item['qty'])];
    $I->seeCodeAndJson(201, $item);
    return $item;
};

/* ********************************************************** */

$I->amGoingTo('add items to cart');
$item1   = $add_item();
$data    = json_decode($I->grabResponse());
$order   = $data->order_id;
$item_id = $data->id;

$I->amGoingTo('add another item to the same order');
$item2 = $add_item();
$I->assertEquals($order, json_decode($I->grabResponse())->order_id, 'The two items are in the same order');
