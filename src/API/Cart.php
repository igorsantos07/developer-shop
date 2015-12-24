<?php namespace Shop\API;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Luracast\Restler\RestException;
use Shop\Model\Item;
use Shop\Model\Order;

class Cart {

    /**
     * Finds or creates an order with the given {@link session_id()}.
     * @param bool $eager if the items should be loaded in advance
     * @return Order
     */
    private function getOrder($eager = true) {
        $data = ['session' => session_id(), 'closed' => Order::ST_OPEN];

        //unable to use firstOrCreate() along with with()
        try {
            return Order::with($eager? 'items' : [])->where($data)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return Order::create($data)->fresh(); //fresh() so we get the default info from the DB as well
        }
    }

    /**
     * Gets the cart details, including its items (or no items).
     */
    public function index() {
        $data = $this->getOrder()->toArray();
        if (!isset($data['items'])) {
            $data['items'] = [];
        }
        return $data;
    }

    /**
     * Adds an item to the cart.
     * @status 201
     * @param string $item  The item name {@from body}
     * @param float  $price The item price {@from body}
     * @return Item
     */
    public function put($item, $price) {
        $item = $this->getOrder(false)->items()->create(compact('item', 'price'));
        return $item->getAttributes();
    }

    /**
     * Clears up the cart.
     * @status 204
     * @return void
     */
    public function delete() {
        $items = Item::where('order_id', $this->getOrder(false)->id)->get();
        $ids   = array_column($items->toArray(), 'id');
        Item::destroy($ids);
    }

    /**
     * Deletes an item from the cart.
     * @param int $id ID for the item to be deleted
     * @status 204
     * @return void
     */
    public function deleteItem($id) {
        Item::destroy($id);
    }

    /**
     * Checkout: closes the order.
     * @throws 406 Not_Acceptable In case the cart is empty
     */
    public function patch() {
        $order = $this->getOrder();
        if (!sizeof($order->items)) {
            throw new RestException(HTTP_NOT_ACCEPTABLE, 'There are no items in the cart to checkout');
        }
        $order->update(['closed' => Order::ST_CLOSED]);
        return $order->toArray();
    }
}