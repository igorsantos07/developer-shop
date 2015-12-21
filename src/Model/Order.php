<?php namespace Shop\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LaravelArdent\Ardent\Ardent;

/**
 * @property string session
 * @property float total
 * @property bool status
 * @property Collection|Item[] items
 * @method HasMany items
 */
class Order extends Base {

    const ST_OPEN   = 0;
    const ST_CLOSED = 1;

    public static $relationsData = [
        'items' => [self::HAS_MANY, Item::class]
    ];

    public function beforeSave() {
        $this->total = $this->items->reduce(function($t, Item $item) {
            return $t + $item->price;
        }, 0);

        $this->status = is_bool($this->status)? : self::ST_OPEN;
    }

}