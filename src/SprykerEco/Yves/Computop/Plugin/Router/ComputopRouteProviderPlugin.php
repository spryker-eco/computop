<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class ComputopRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const CREDIT_CARD_SUCCESS = 'computop-credit-card-success';

    /**
     * @var string
     */
    public const DIRECT_DEBIT_SUCCESS = 'computop-direct-debit-success';

    /**
     * @var string
     */
    public const EASY_CREDIT_SUCCESS = 'computop-easy-credit-success';

    /**
     * @var string
     */
    public const IDEAL_SUCCESS = 'computop-ideal-success';

    /**
     * @var string
     */
    public const PAYDIREKT_SUCCESS = 'computop-paydirekt-success';

    /**
     * @var string
     */
    public const PAY_NOW_SUCCESS = 'computop-paynow-success';

    /**
     * @var string
     */
    public const PAY_PAL_SUCCESS = 'computop-pay-pal-success';

    /**
     * @var string
     */
    public const SOFORT_SUCCESS = 'computop-sofort-success';

    /**
     * @var string
     */
    public const PAYU_CEE_SINGLE_SUCCESS = 'computop-payu-cee-single-success';

    /**
     * @var string
     */
    public const FAILURE_PATH_NAME = 'computop-failure';

    /**
     * @var string
     */
    public const NOTIFY_PATH_NAME = 'computop-notify';

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addCreditCardSuccessRoute($routeCollection);
        $routeCollection = $this->addDirectDebitSuccessRoute($routeCollection);
        $routeCollection = $this->addEasyCreditSuccessRoute($routeCollection);
        $routeCollection = $this->addIdealSuccessRoute($routeCollection);
        $routeCollection = $this->addPaydirectSuccessRoute($routeCollection);
        $routeCollection = $this->addPayNowSuccessRoute($routeCollection);
        $routeCollection = $this->addPayPalSuccessRoute($routeCollection);
        $routeCollection = $this->addSofortSuccessRoute($routeCollection);
        $routeCollection = $this->addFailureRoute($routeCollection);
        $routeCollection = $this->addNotifyRoute($routeCollection);
        $routeCollection = $this->addPayuCeeSingleSuccessRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCreditCardSuccessRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/computop/credit-card-success',
            'Computop',
            'Callback',
            'successCreditCardAction'
        );
        $routeCollection->add(static::CREDIT_CARD_SUCCESS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addDirectDebitSuccessRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/computop/direct-debit-success',
            'Computop',
            'Callback',
            'successDirectDebitAction'
        );
        $routeCollection->add(static::DIRECT_DEBIT_SUCCESS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addEasyCreditSuccessRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/computop/easy-credit-success',
            'Computop',
            'Callback',
            'successEasyCreditAction'
        );
        $routeCollection->add(static::EASY_CREDIT_SUCCESS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addIdealSuccessRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/computop/ideal-success',
            'Computop',
            'Callback',
            'successIdealAction'
        );
        $routeCollection->add(static::IDEAL_SUCCESS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPaydirectSuccessRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/computop/paydirekt-success',
            'Computop',
            'Callback',
            'successPaydirektAction'
        );
        $routeCollection->add(static::PAYDIREKT_SUCCESS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPayNowSuccessRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/computop/paynow-success',
            'Computop',
            'Callback',
            'successPayNowAction'
        );
        $routeCollection->add(static::PAY_NOW_SUCCESS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPayPalSuccessRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/computop/pay-pal-success',
            'Computop',
            'Callback',
            'successPayPalAction'
        );
        $routeCollection->add(static::PAY_PAL_SUCCESS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSofortSuccessRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/computop/sofort-success',
            'Computop',
            'Callback',
            'successSofortAction'
        );
        $routeCollection->add(static::SOFORT_SUCCESS, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerEco\Yves\Computop\Controller\CallbackController::successPayuCeeSingleAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPayuCeeSingleSuccessRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/computop/payu-cee-single-success',
            'Computop',
            'Callback',
            'successPayuCeeSingleAction'
        );
        $routeCollection->add(static::PAYU_CEE_SINGLE_SUCCESS, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerEco\Yves\Computop\Controller\CallbackController::failureAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addFailureRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/computop/failure',
            'Computop',
            'Callback',
            'failureAction'
        );
        $routeCollection->add(static::FAILURE_PATH_NAME, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addNotifyRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/computop/notify',
            'Computop',
            'Callback',
            'notifyAction'
        );
        $routeCollection->add(static::NOTIFY_PATH_NAME, $route);

        return $routeCollection;
    }
}
