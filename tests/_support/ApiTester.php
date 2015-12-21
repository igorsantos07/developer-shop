<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    /**
     * Verifies the response code, JSON validity and JSON inclusion at once.
     * @param int $code
     * @param array $json
     * @see seeResponseCodeIs
     * @see seeResponseIsJson
     * @see seeResponseContainsJson
     */
   public function seeCodeAndJson($code, array $json) {
       $this->seeResponseCodeIs($code);
       $this->seeResponseIsJson();
       $this->seeResponseContainsJson($json);
   }
}
