<?php namespace Shop\API;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Luracast\Restler\RestException;
use Shop\Model\Coupon;

class CartCoupon {

    public static $url = '/cart/coupon';

    use traits\OrderRelated;

    private function getCoupon($code) {
        try {
            return Coupon::where('code', 'ilike', $code)->firstOrFail();
        }
        catch (ModelNotFoundException $e) {
            throw new RestException(HTTP_NOT_FOUND, 'Coupon not found', ['coupon' => $code]);
        }
    }

    /**
     * Adds a coupon code to the cart.
     * @param string $code The coupon
     * @throws 404 Not_Found Coupon does not exist
     * @return float
     */
    public function post($code) {
        $coupon = $this->getCoupon($code);
        $order  = $this->getOrder(false);
        $order->coupon()->associate($coupon);
        $order->save();
        return $coupon->discount;
    }

    /**
     * @param string $code
     * @status 204
     * @throws 409 Conflict Given coupon is not the one the cart
     * @throws 412 Expectation_Failed There are no coupons in the order any longer
     */
    public function delete($code = null) {
        $order = $this->getOrder('coupon');
        if (!$order->coupon_id) {
            throw new RestException(HTTP_EXPECTATION_FAILED, 'Order has no coupon');
        }
        $dissociate = function() use ($order) {
            $order->coupon()->dissociate();
            $order->save();
        };

        if ($code) {
            //we have to use getCoupon here, or else a 404 would not be thrown for unknown coupons
            if ($order->coupon_id == $this->getCoupon($code)->id) {
                $dissociate();
            } else {
                throw new RestException(HTTP_CONFLICT, 'This coupon is not present in the cart', [
                    'given'       => $code,
                    'cart_coupon' => $order->coupon->code
                ]);
            }
        } else {
            $dissociate();
        }
    }

}