<?php namespace Shop\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int               id        Order ID
 * @property string            session   Order Session ID
 * @property string|float      total     Total cart price to be paid
 * @property bool              closed    0 if the cart is open, 1 if the order was closed
 * @property Collection|Item[] items     List of items in the cart
 * @property Coupon            coupon    Discount coupon
 * @property int               coupon_id Discount coupon ID
 * @method HasMany items
 * @method BelongsTo coupon
 */
class Order extends Base {

    const ST_OPEN = 0;
    const ST_CLOSED = 1;

    public static $relationsData = [
        'items'  => [self::HAS_MANY, Item::class],
        'coupon' => [self::BELONGS_TO, Coupon::class]
    ];

    public function setCouponIdAttribute($id) {
        if ($id == $this->coupon_id) { //nothing to do here, let's skip all the queries!
            return;
        }

        $discount = $id? Coupon::findOrFail($id)->discount : 0;
        foreach ($this->items as $item) {
            $item->setDiscount($discount);
            $item->save();
        }

        $this->attributes['coupon_id'] = $id;
    }

    public function getTotalAttribute() {
        return self::decimal($this->attributes['total']);
    }
}