<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop;

use Codeception\Actor;
use Codeception\Scenario;
use SprykerEco\Shared\Computop\ComputopConfig;

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

    /**
     * @param \Codeception\Scenario $scenario
     */
    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);
        $this->setUpConfig();
    }

    /**
     * Set up config
     *
     * @return void
     */
    public function setUpConfig()
    {
        $this->setConfig('COMPUTOPAPI:MERCHANT_ID', 'COMPUTOP:MERCHANT_ID');
        $this->setConfig('COMPUTOPAPI:HMAC_PASSWORD', 'COMPUTOP:HMAC_PASSWORD');
        $this->setConfig('COMPUTOPAPI:BLOWFISH_PASSWORD', 'COMPUTOP:BLOWFISH_PASSWORD');
        $this->setConfig('COMPUTOP:RESPONSE_MAC_REQUIRED', ['INIT']);
        $this->setConfig('COMPUTOP:PAYMENT_METHODS_WITHOUT_ORDER_CALL', ['computopSofort', 'computopPaydirekt', 'computopIdeal']);
        $this->setConfig('COMPUTOP:SOFORT_INIT_ACTION', 'https://www.computop-paygate.com/sofort.aspx');
        $this->setConfig(
            'COMPUTOP:CRIF_GREEN_AVAILABLE_PAYMENT_METHODS',
            [
                ComputopConfig::PAYMENT_METHOD_SOFORT,
                ComputopConfig::PAYMENT_METHOD_PAYDIREKT,
                ComputopConfig::PAYMENT_METHOD_IDEAL,
                ComputopConfig::PAYMENT_METHOD_CREDIT_CARD,
                ComputopConfig::PAYMENT_METHOD_PAY_NOW,
                ComputopConfig::PAYMENT_METHOD_PAY_PAL,
                ComputopConfig::PAYMENT_METHOD_DIRECT_DEBIT,
                ComputopConfig::PAYMENT_METHOD_EASY_CREDIT,
            ]
        );
        $this->setConfig(
            'COMPUTOP:CRIF_YELLOW_AVAILABLE_PAYMENT_METHODS',
            [
                ComputopConfig::PAYMENT_METHOD_CREDIT_CARD,
                ComputopConfig::PAYMENT_METHOD_PAY_NOW,
                ComputopConfig::PAYMENT_METHOD_PAY_PAL,
            ]
        );
        $this->setConfig(
            'COMPUTOP:CRIF_RED_AVAILABLE_PAYMENT_METHODS',
            [
                ComputopConfig::PAYMENT_METHOD_CREDIT_CARD,
            ]
        );
        $this->setConfig('COMPUTOP:CRIF_ENABLED', true);
    }
}
