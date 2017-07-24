<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Computop\Plugin\Provider;

use Pyz\Yves\Application\Plugin\Provider\AbstractYvesControllerProvider;
use Silex\Application;

class ComputopControllerProvider extends AbstractYvesControllerProvider
{

    const NAME = 'computop';
    const BUNDLE_NAME = 'Computop';
    const CALLBACK_CONTROLLER_NAME = 'Callback';

    const SUCCESS_PATH = '/computop/success';
    const FAILURE_PATH = '/computop/failure';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->createController(
            self::SUCCESS_PATH,
            'computop-success',
            self::BUNDLE_NAME,
            self::CALLBACK_CONTROLLER_NAME,
            'success'
        );

        $this->createController(
            self::FAILURE_PATH,
            'computop-failure',
            self::BUNDLE_NAME,
            self::CALLBACK_CONTROLLER_NAME,
            'failure'
        );
    }

}
