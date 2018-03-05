<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController as SprykerAbstractController;

abstract class AbstractController extends SprykerAbstractController
{
    /**
     * @var array
     */
    protected $responseArray;

    /**
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->responseArray = $this->getApplication()['request']->query->all();
    }
}
