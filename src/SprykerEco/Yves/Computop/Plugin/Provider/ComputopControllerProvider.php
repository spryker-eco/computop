<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Plugin\Provider;

use Silex\Application;
use Spryker\Yves\Application\Plugin\Provider\YvesControllerProvider;

class ComputopControllerProvider extends YvesControllerProvider
{
    const CREDIT_CARD_SUCCESS = 'computop-credit-card-success';
    const DIRECT_DEBIT_SUCCESS = 'computop-direct-debit-success';
    const EASY_CREDIT_SUCCESS = 'computop-easy-credit-success';
    const IDEAL_SUCCESS = 'computop-ideal-success';
    const PAYDIREKT_SUCCESS = 'computop-paydirekt-success';
    const PAY_PAL_SUCCESS = 'computop-pay-pal-success';
    const SOFORT_SUCCESS = 'computop-sofort-success';

    const FAILURE_PATH_NAME = 'computop-failure';
    const NOTIFY_PATH_NAME = 'computop-notify';

    /**
     * @var string
     */
    protected $moduleName = 'Computop';
    
    /**
     * @var string
     */
    protected $callbackControllerName = 'Callback';
    
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->createController(
            '/computop/credit-card-success',
            self::CREDIT_CARD_SUCCESS,
            $this->moduleName,
            $this->callbackControllerName,
            'successCreditCard'
        );

        $this->createController(
            '/computop/direct-debit-success',
            self::DIRECT_DEBIT_SUCCESS,
            $this->moduleName,
            $this->callbackControllerName,
            'successDirectDebit'
        );

        $this->createController(
            '/computop/easy-credit-success',
            self::EASY_CREDIT_SUCCESS,
            $this->moduleName,
            $this->callbackControllerName,
            'successEasyCredit'
        );

        $this->createController(
            '/computop/ideal-success',
            self::IDEAL_SUCCESS,
            $this->moduleName,
            $this->callbackControllerName,
            'successIdeal'
        );

        $this->createController(
            '/computop/paydirekt-success',
            self::PAYDIREKT_SUCCESS,
            $this->moduleName,
            $this->callbackControllerName,
            'successPaydirekt'
        );

        $this->createController(
            '/computop/pay-pal-success',
            self::PAY_PAL_SUCCESS,
            $this->moduleName,
            $this->callbackControllerName,
            'successPayPal'
        );

        $this->createController(
            '/computop/sofort-success',
            self::SOFORT_SUCCESS,
            $this->moduleName,
            $this->callbackControllerName,
            'successSofort'
        );

        $this->createController(
            '/computop/failure',
            self::FAILURE_PATH_NAME,
            $this->moduleName,
            $this->callbackControllerName,
            'failure'
        );

        $this->createController(
            '/computop/notify',
            self::NOTIFY_PATH_NAME,
            $this->moduleName,
            $this->callbackControllerName,
            'notify'
        );
    }
}
