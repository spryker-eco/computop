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
    const NAME = 'computop';
    const MODULE_NAME = 'Computop';
    const CALLBACK_CONTROLLER_NAME = 'Callback';

    const CREDIT_CARD_SUCCESS_PATH = '/computop/credit-card-success';
    const CREDIT_CARD_SUCCESS_PATH_NAME = 'computop-credit-card-success';

    const PAY_PAL_SUCCESS_PATH = '/computop/pay-pal-success';
    const PAY_PAL_SUCCESS_PATH_NAME = 'computop-pay-pal-success';

    const DIRECT_DEBIT_SUCCESS_PATH = '/computop/direct-debit-success';
    const DIRECT_DEBIT_SUCCESS_PATH_NAME = 'computop-direct-debit-success';

    const SOFORT_SUCCESS_PATH = '/computop/sofort-success';
    const SOFORT_SUCCESS_PATH_NAME = 'computop-sofort-success';

    const PAYDIREKT_SUCCESS_PATH = '/computop/paydirekt-success';
    const PAYDIREKT_SUCCESS_PATH_NAME = 'computop-paydirekt-success';

    const IDEAL_SUCCESS_PATH = '/computop/ideal-success';
    const IDEAL_SUCCESS_PATH_NAME = 'computop-ideal-success';

    const FAILURE_PATH = '/computop/failure';
    const FAILURE_PATH_NAME = 'computop-failure';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->createController(
            self::CREDIT_CARD_SUCCESS_PATH,
            self::CREDIT_CARD_SUCCESS_PATH_NAME,
            self::MODULE_NAME,
            self::CALLBACK_CONTROLLER_NAME,
            'successCreditCard'
        );

        $this->createController(
            self::PAY_PAL_SUCCESS_PATH,
            self::PAY_PAL_SUCCESS_PATH_NAME,
            self::MODULE_NAME,
            self::CALLBACK_CONTROLLER_NAME,
            'successPayPal'
        );

        $this->createController(
            self::DIRECT_DEBIT_SUCCESS_PATH,
            self::DIRECT_DEBIT_SUCCESS_PATH_NAME,
            self::MODULE_NAME,
            self::CALLBACK_CONTROLLER_NAME,
            'successDirectDebit'
        );

        $this->createController(
            self::SOFORT_SUCCESS_PATH,
            self::SOFORT_SUCCESS_PATH_NAME,
            self::MODULE_NAME,
            self::CALLBACK_CONTROLLER_NAME,
            'successSofort'
        );

        $this->createController(
            self::PAYDIREKT_SUCCESS_PATH,
            self::PAYDIREKT_SUCCESS_PATH_NAME,
            self::MODULE_NAME,
            self::CALLBACK_CONTROLLER_NAME,
            'successPaydirekt'
        );

        $this->createController(
            self::IDEAL_SUCCESS_PATH,
            self::IDEAL_SUCCESS_PATH_NAME,
            self::MODULE_NAME,
            self::CALLBACK_CONTROLLER_NAME,
            'successIdeal'
        );

        $this->createController(
            self::FAILURE_PATH,
            self::FAILURE_PATH_NAME,
            self::MODULE_NAME,
            self::CALLBACK_CONTROLLER_NAME,
            'failure'
        );
    }
}
