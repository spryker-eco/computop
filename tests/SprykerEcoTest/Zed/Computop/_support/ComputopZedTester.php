<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop;

use Codeception\Actor;
use Codeception\Scenario;

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
class ComputopZedTester extends Actor
{
    use _generated\ComputopZedTesterActions;

    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);
        $this->setUpConfig();
    }

    /**
     * Set up config
     */
    public function setUpConfig()
    {
        $this->setConfig('COMPUTOP:MERCHANT_ID', 'COMPUTOP:MERCHANT_ID');
        $this->setConfig('COMPUTOP:HMAC_PASSWORD', 'COMPUTOP:HMAC_PASSWORD');
        $this->setConfig('COMPUTOP:BLOWFISH_PASSWORD', 'COMPUTOP:BLOWFISH_PASSWORD');
    }
}
