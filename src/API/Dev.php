<?php namespace Shop\API;

use Luracast\Restler\RestException;
use Shop\Model\Developer;

class Dev {

    /**
     * Gets details about a developer from GitHub, including an expected price.
     * @param string $username {@from path}
     * @throws 404 Username not found
     * @return Developer
     */
    public function get($username) {
        try {
            $dev = new Developer($username);
            return $dev;
        }
        catch (\Exception $e) {
            if ($e->getCode() == Developer::ERR_NOT_FOUND) {
                throw new RestException(HTTP_NOT_FOUND, 'Username not found in GitHub', [
                    'username' => $username,
                    'url'      => $e->getMessage()
                ], $e);
            } else {
                throw $e;
            }
        }
    }
}
