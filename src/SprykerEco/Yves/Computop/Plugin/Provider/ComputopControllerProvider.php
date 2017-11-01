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
    const PAY_PAL_SUCCESS = 'computop-pay-pal-success';
    const DIRECT_DEBIT_SUCCESS = 'computop-direct-debit-success';
    const SOFORT_SUCCESS = 'computop-sofort-success';
    const PAYDIREKT_SUCCESS = 'computop-paydirekt-success';
    const IDEAL_SUCCESS = 'computop-ideal-success';
    const FAILURE_PATH_NAME = 'computop-failure';

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
            '/computop/pay-pal-success',
            self::PAY_PAL_SUCCESS,
            $this->moduleName,
            $this->callbackControllerName,
            'successPayPal'
        );

        $this->createController(
            '/computop/direct-debit-success',
            self::DIRECT_DEBIT_SUCCESS,
            $this->moduleName,
            $this->callbackControllerName,
            'successDirectDebit'
        );

        $this->createController(
            '/computop/sofort-success',
            self::SOFORT_SUCCESS,
            $this->moduleName,
            $this->callbackControllerName,
            'successSofort'
        );

        $this->createController(
            '/computop/paydirekt-success',
            self::PAYDIREKT_SUCCESS,
            $this->moduleName,
            $this->callbackControllerName,
            'successPaydirekt'
        );

        $this->createController(
            '/computop/ideal-success',
            self::IDEAL_SUCCESS,
            $this->moduleName,
            $this->callbackControllerName,
            'successIdeal'
        );

        $this->createController(
            '/computop/failure',
            self::FAILURE_PATH_NAME,
            $this->moduleName,
            $this->callbackControllerName,
            'failure'
        );
    }
}
