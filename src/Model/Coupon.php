<?php namespace Shop\Model;

/**
 * @property string code
 * @property float  discount
 */
class Coupon extends Base {

    public function getDiscountAttribute() {
        return self::monetary($this->attributes['discount'] / 100);
    }

}