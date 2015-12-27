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

        $update_items = function($discount) {
            //using $this->items()->get() instead of $this->items forces us to always receive new items from the db.
            //this is useful when we remove a coupon and add another at the same time - if we used the same
            //pool of items, each item would still hold their individual copy of the order - with an old total
            //todo: this could be query-optimized if we had three cases: add coupon, remove coupon, change coupon (computing the discount difference instead of running the other two cases in separate)
            foreach ($this->items()->get() as $item) { /** @var Item $item */
                $item->setDiscount($discount);
                $item->save();
            }
        };

        if ($this->coupon_id) { //removes the old coupon, if it exists
            $update_items($this->coupon->discount * -1);
        }

        if ($id) {  //adds the new coupon, if it exists
            $update_items(Coupon::findOrFail($id)->discount);
        }

        $this->attributes['coupon_id'] = $id;
    }

    public function getTotalAttribute() {
        return self::monetary($this->attributes['total']);
    }
}