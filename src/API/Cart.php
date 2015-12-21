<?php namespace Shop\API;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Shop\Model\Item;
use Shop\Model\Order;

class Cart {

    /**
     * Finds or creates an order with the given {@link session_id()}.
     * @param bool $eager if the items should be loaded in advance
     * @return Order
     */
    private function getOrder($eager = true) {
        $data = ['session' => session_id()];

        //unable to use firstOrCreate() along with with()
        try {
            return Order::with($eager? 'items' : [])->where($data)->firstOrFail();
        }
        catch (ModelNotFoundException $e) {
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
     * @param string $item The item name {@from body}
     * @param float $price The item price {@from body}
     * @return Item
     */
    public function put($item, $price) {
        $item  = $this->getOrder(false)->items()->create(compact('item', 'price'));
        return $item->getAttributes();
    }

}