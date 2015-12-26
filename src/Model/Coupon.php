<?php namespace Shop\Model;
use LaravelArdent\Ardent\Ardent;

/**
 * @property string code
 * @property float  discount
 */
class Coupon extends Ardent {

    public function getDiscountAttribute() {
        return $this->attributes['discount'] / 100;
    }

}