<?php namespace Shop\API;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Luracast\Restler\RestException;
use Shop\Model\Developer;

class Dev {

    private function respondOrFail(callable $response, $error_msg = 'Not found in GitHub', array $details = []) {
        try {
            return $response();
        }
        catch (ModelNotFoundException $e) {
            throw new RestException(HTTP_NOT_FOUND, $error_msg, array_merge($details, ['url' => $e->getMessage()]), $e);
        }
    }

    /**
     * Gets details about a developer from GitHub, including an expected price.
     * @param string $username {@from path}
     * @throws 404 Not_Found Username not found
     * @return Developer
     */
    public function get($username) {
        return $this->respondOrFail(function() use ($username) {
            return new Developer($username);
        },'Username not found in GitHub', compact('username'));
    }

    /**
     * Lists all members for an organization.
     * There are three levels of information that can be given:
     * <ul style="list-style: square inside">
     *  <li><b>basic</b>:
     *      Shows only the username, URL and Avatar. The rest is empty. This is the fastest response.
     *  </li>
     *  <li><b>user</b> (default):
     *      Includes all data, such as location, bio and basic stats, except the hourly rate.
     *      Can take some seconds to finish, depending on the organization size.
     *  </li>
     *  <li><b>complete</b>:
     *      Includes the user information and the hourly rate. As this requests needs a lot of information
     *      from GitHub, it might take a while. Avoid using it. However, as data is processed and cached by user,
     *      you can retry it a few times if a timeout is received.
     *  </li>
     * </ul>
     *
     * @param string $org {@from path}
     * @param string $level {@choice basic,user,complete}
     * @throws 404 Not_Found Organization not found
     * @return Developer[]
     */
    public function getOrganization($org, $level = Developer::ORG_USER_INFO) {
        return $this->respondOrFail(function() use ($org, $level) {
            $members = Developer::listFromOrganization($org, $level);
            return [
                'size'    => sizeof($members),
                'members' => $members
            ];
        }, 'Organization not found in GitHub', ['organization' => $org]);
    }
}
