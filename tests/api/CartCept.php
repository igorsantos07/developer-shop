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
$I->sendPUT('cart', $new_item);
$I->seeCodeAndJson(201, $new_item);
$order2 = json_decode($I->grabResponse())->order_id;
$I->assertEquals($order, $order2, 'The two items are in the same order');

$I->amGoingTo('Verify order');
$I->sendGET('cart');
$I->expectTo('see items in order');
$I->seeCodeAndJson(200, ['items' => [$item, $new_item]]);
$I->expectTo('see correct total');
$I->seeResponseContainsJson(['total' => $item['price'] + $new_item['price']]);
