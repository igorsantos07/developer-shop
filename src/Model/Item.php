<?php namespace Shop\Model;

/**
 * @property string $item     Item name
 * @property float  $price    Item price
 * @property int    $order_id The order ID
 * @property Order  $order
 */
class Item extends Base {

    protected $old_price;

    protected $casts = [
        'price' => 'float'
    ];

    public static $relationsData = [
        'order' => [self::BELONGS_TO, Order::class]
    ];

    /**
     * Stores the old price so we can correctly update the order value when it's saved.
     * @param $price
     */
    public function setPriceAttribute($price) {
        $this->old_price           = $this->price;
        $this->attributes['price'] = $price;
    }

    public function afterSave() {
        $this->order->total += $this->price;
        $this->order->save();
    }

    /**
     * Removes the old price from the order, but do not save it yet. When afterSave() occurs,
     * the new price will be included.
     */
    public function beforeUpdate() {
        $this->order->total -= $this->old_price;
    }

    public function afterDelete() {
        $this->order->total -= $this->price;
        $this->order->save();
    }
}