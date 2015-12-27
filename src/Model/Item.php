<?php namespace Shop\Model;

/**
 * @property string       $item     Item name
 * @property float $price    Item price
 * @property int          $order_id The order ID
 * @property Order        $order
 */
class Item extends Base {

    protected $old_price;

    public static $relationsData = [
        'order' => [self::BELONGS_TO, Order::class]
    ];

    /**
     * Stores the old price so we can correctly update the order value when it's saved.
     * @param $price
     */
    public function setPriceAttribute($price) {
        $this->old_price           = $this->price;
        $this->attributes['price'] = self::monetary($price);
    }

    public function getPriceAttribute() {
        return self::monetary(isset($this->attributes['price'])? $this->attributes['price'] : 0);
    }

    /**
     * Alters the price given a $discount percentage.
     * @param float $discount
     */
    public function setDiscount($discount) {
        $multiplier = ($discount > 0)?
            1 - $discount : // if the discount is 20% (0.2), the final price is 80% of the original
            1 / (1 - (-$discount)); //the other way around, using rule of three, and accounting for the neg given
        $this->setPriceAttribute($this->price * $multiplier);
    }

    public function afterSave() {
        $this->order->total += $this->price;
        $this->order->save();
    }

    public function beforeCreate() {
        if ($this->order->coupon_id) {
            $this->setDiscount($this->order->coupon->discount);
        }
        return true;
    }

    /**
     * Removes the old price from the order, but do not save it yet. When afterSave() occurs,
     * the new price will be included.
     */
    public function beforeUpdate() {
        $this->order->total -= $this->old_price;
        return true;
    }

    public function afterDelete() {
        $this->order->total -= $this->price;
        $this->order->save();
    }
}