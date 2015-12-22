<?php namespace Shop\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int id
 * @property string session
 * @property float total
 * @property bool status
 * @property Collection|Item[] items
 * @method HasMany items
 */
class Order extends Base {

    const ST_OPEN   = 0;
    const ST_CLOSED = 1;

    protected $casts = [
        'total' => 'float'
    ];

    public static $relationsData = [
        'items' => [self::HAS_MANY, Item::class]
    ];

}