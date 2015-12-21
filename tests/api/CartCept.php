<?php
$devs = \Codeception\Util\Fixtures::get('devs');
$I = new ApiTester($scenario);
$I->wantTo('add items to cart');
$I->sendPUT('/cart', [
    'product' => $devs[array_rand($devs)],
    'price'   => rand(10.01, 1000.99),
]);
$I->seeResponseCodeIs(201);
$I->seeResponseIsJson();
