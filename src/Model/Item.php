<?php namespace Shop\Model;

/**
 * @property string $item Item name
 * @property float $price Item price
 * @property int $order_id The order ID
 * @property Order $order
 */
class Item extends Base {

    protected $casts = [
        'price' => 'float'
    ];

    public static $relationsData = [
        'order' => [self::BELONGS_TO, Order::class]
    ];

    public function afterSave() {
        $this->order->total += $this->price;
        $this->order->save();
    }

}