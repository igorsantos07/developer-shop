<?php namespace Shop\Model;
use LaravelArdent\Ardent\Ardent;

/**
 * @property string $item Item name
 * @property float $price Item price
 * @property int $order_id The order ID
 */
class Item extends Base {

    public static $relationsData = [
        'order' => [self::BELONGS_TO, Order::class]
    ];

}