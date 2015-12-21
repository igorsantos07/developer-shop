<?php namespace Shop\API;
use Luracast\Restler\RestException;
use Shop\Model\Item;
use Shop\Model\Order;

class Cart {

    /**
     * Gets all items in the cart for the given user session.
     * @param int $id
     * @throws 501
     */
    public function get($id) {
        throw new RestException(HTTP_NOT_IMPLEMENTED);
    }

    /**
     * Adds an item to the cart.
     * @status 201
     * @param string $item The item name {@from body}
     * @param float $price The item price {@from body}
     * @return Item
     */
    public function put($item, $price) {
        $order = Order::firstOrCreate(['session' => session_id()]);
        $item  = $order->items()->create(compact('item', 'price'));
        return $item->getAttributes();
    }

}