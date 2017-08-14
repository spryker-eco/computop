<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
    const SUCCESS_PATH_NAME = 'computop-success';

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
            self::SUCCESS_PATH,
            self::SUCCESS_PATH_NAME,
            self::BUNDLE_NAME,
            self::CALLBACK_CONTROLLER_NAME,
            'success'
        );

        $this->createController(
            self::FAILURE_PATH,
            self::FAILURE_PATH_NAME,
            self::BUNDLE_NAME,
            self::CALLBACK_CONTROLLER_NAME,
            'failure'
        );
    }

}
