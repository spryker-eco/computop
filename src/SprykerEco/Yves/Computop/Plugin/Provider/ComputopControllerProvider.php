<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Plugin\Provider;

use Silex\Application;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `{@link \SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin}` instead.
 */
class ComputopControllerProvider extends AbstractYvesControllerProvider
{
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
            ComputopRouteProviderPlugin::CREDIT_CARD_SUCCESS,
            $this->moduleName,
            $this->callbackControllerName,
            'successCreditCard'
        );

        $this->createController(
            '/computop/paynow-success',
            ComputopRouteProviderPlugin::PAY_NOW_SUCCESS,
            $this->moduleName,
            $this->callbackControllerName,
            'successPayNow'
        );

        $this->createController(
            '/computop/direct-debit-success',
            ComputopRouteProviderPlugin::DIRECT_DEBIT_SUCCESS,
            $this->moduleName,
            $this->callbackControllerName,
            'successDirectDebit'
        );

        $this->createController(
            '/computop/easy-credit-success',
            ComputopRouteProviderPlugin::EASY_CREDIT_SUCCESS,
            $this->moduleName,
            $this->callbackControllerName,
            'successEasyCredit'
        );

        $this->createController(
            '/computop/ideal-success',
            ComputopRouteProviderPlugin::IDEAL_SUCCESS,
            $this->moduleName,
            $this->callbackControllerName,
            'successIdeal'
        );

        $this->createController(
            '/computop/paydirekt-success',
            ComputopRouteProviderPlugin::PAYDIREKT_SUCCESS,
            $this->moduleName,
            $this->callbackControllerName,
            'successPaydirekt'
        );

        $this->createController(
            '/computop/pay-pal-success',
            ComputopRouteProviderPlugin::PAY_PAL_SUCCESS,
            $this->moduleName,
            $this->callbackControllerName,
            'successPayPal'
        );

        $this->createController(
            '/computop/sofort-success',
            ComputopRouteProviderPlugin::SOFORT_SUCCESS,
            $this->moduleName,
            $this->callbackControllerName,
            'successSofort'
        );

        $this->createController(
            '/computop/payu-cee-single-success',
            ComputopRouteProviderPlugin::PAYU_CEE_SINGLE_SUCCESS,
            $this->moduleName,
            $this->callbackControllerName,
            'successPayuCeeSingle'
        );

        $this->createController(
            '/computop/failure',
            ComputopRouteProviderPlugin::FAILURE_PATH_NAME,
            $this->moduleName,
            $this->callbackControllerName,
            'failure'
        );

        $this->createController(
            '/computop/notify',
            ComputopRouteProviderPlugin::NOTIFY_PATH_NAME,
            $this->moduleName,
            $this->callbackControllerName,
            'notify'
        );
    }
}
