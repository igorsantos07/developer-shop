<?php namespace Shop\API\traits;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use \Shop\Model\Order;

trait OrderRelated {

    /**
     * Finds or creates an order with the given {@link session_id()}.
     * @param bool|string|array $eager if relations should be loaded in advance.
     *                                 True loads all, a string or an array of strings loads only those relations.
     * @return Order
     */
    private function getOrder($eager = true) {
        $data = ['session' => session_id(), 'closed' => Order::ST_OPEN];

        //unable to use firstOrCreate() along with with()
        try {
            if ($eager) {
                $eager = is_bool($eager)? ['items','coupon'] : $eager;
            }

            return Order::with($eager?: [])->where($data)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return Order::create($data)->fresh(); //fresh() so we get the default info from the DB as well
        }
    }

}
