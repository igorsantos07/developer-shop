<?php namespace Shop\API;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Luracast\Restler\RestException;
use Shop\Model\Item;
use Shop\Model\Order;

class Cart {

    /**
     * Finds or creates an order with the given {@link session_id()}.
     * @return Order
     */
    private function getOrder($eager = true) {
        $data = ['session' => session_id()];

        //unable to use firstOrCreate() along with with()
        try {
            return Order::with($eager? 'items' : [])->where($data)->firstOrFail();
        }
        catch (ModelNotFoundException $e) {
            return Order::create($data);
        }
    }

    /**
     * Gets all items in the cart for the given user session.
     */
    public function index() {
        return $this->getOrder()->toArray();
    }

    /**
     * Adds an item to the cart.
     * @status 201
     * @param string $item The item name {@from body}
     * @param float $price The item price {@from body}
     * @return Item
     */
    public function put($item, $price) {
        $item  = $this->getOrder(false)->items()->create(compact('item', 'price'));
        return $item->getAttributes();
    }

}