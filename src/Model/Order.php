<?php namespace Shop\Model;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int               id      Order ID
 * @property string            session Order Session ID
 * @property float             total   Total cart price to be paid
 * @property bool              closed  0 if the cart is open, 1 if the order was closed
 * @property Collection|Item[] items   List of items in the cart
 * @method HasMany items xxxx
 */
class Order extends Base {

    const ST_OPEN = 0;
    const ST_CLOSED = 1;

    protected $casts = [
        'total' => 'float'
    ];

    public static $relationsData = [
        'items' => [self::HAS_MANY, Item::class]
    ];
}