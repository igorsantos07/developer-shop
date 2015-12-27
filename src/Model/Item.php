<?php namespace Shop\Model;

/**
 * @property string $item         Item name
 * @property float  $price        Unitary price
 * @property float  $qty          Quantity of the given item
 * @property float  $final_price  Item final price, based on price X qty
 * @property int    $order_id     The order ID
 * @property Order  $order
 */
class Item extends Base {

    protected $old_final;

    public static $relationsData = [
        'order' => [self::BELONGS_TO, Order::class]
    ];

    public function setPriceAttribute($price) {
        $this->attributes['price'] = self::decimal($price);
        if ($this->qty) {
            $this->calculateFinalPrice();
        }
    }

    public function setQtyAttribute($qty) {
        $this->attributes['qty'] = self::decimal($qty);
        if ($this->price) {
            $this->calculateFinalPrice();
        }
    }

    /**
     * Calculates the final price based on Qty and price, and stores the old value
     * so we can correctly update the order value when it's saved.
     * @param int|float $discount_multiplier If there's a discount, the multiplier is given here
     */
    protected function calculateFinalPrice($discount_multiplier = 1) {
        $this->old_final = $this->final_price;
        $full_price = self::decimal($this->qty * $this->price);
        $this->attributes['final_price'] = self::decimal($full_price * $discount_multiplier);
    }

    /**
     * The final price is calculated after Qty and Price were defined.
     */
    public function setFinalPriceAttribute() {
        throw new \OutOfBoundsException('final_price is not writable');
    }

    public function getPriceAttribute() { return $this->decimalValueFor('price'); }
    public function getQtyAttribute() { return $this->decimalValueFor('qty'); }
    public function getFinalPriceAttribute() { return $this->decimalValueFor('final_price'); }

    public function decimalValueFor($field) {
        return self::decimal(isset($this->attributes[$field])? $this->attributes[$field] : 0);
    }

    /**
     * Alters the price given a $discount percentage.
     * @param float $discount
     */
    public function setDiscount($discount) {
        $this->calculateFinalPrice(1 - $discount);
    }

    public function beforeCreate() {
        if ($this->order->coupon_id) {
            $this->setDiscount($this->order->coupon->discount);
        }
        return true;
    }

    public function afterSave() {
        $this->order->total += $this->final_price;
        $this->order->save();
    }

    /**
     * Removes the old price from the order, but does not save it yet.
     * When afterSave() is called the new price will be included.
     */
    public function beforeUpdate() {
        $this->order->total -= $this->old_final;
        return true;
    }

    public function afterDelete() {
        $this->order->total -= $this->final_price;
        $this->order->save();
    }
}