<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace;

use SprykerEco\Zed\Computop\Business\Api\Request\PostPlace\PostPlaceRequestInterface;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\SaverInterface;

abstract class AbstractHandler implements HandlerInterface
{
    /**
     * @var \SprykerEco\Zed\Computop\Business\Api\Request\PostPlace\PostPlaceRequestInterface
     */
    protected $request;

    /**
     * @var \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\SaverInterface
     */
    protected $saver;

    /**
     * @param \SprykerEco\Zed\Computop\Business\Api\Request\PostPlace\PostPlaceRequestInterface $request
     * @param \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\SaverInterface $saver
     */
    public function __construct(
        PostPlaceRequestInterface $request,
        SaverInterface $saver
    ) {
        $this->request = $request;
        $this->saver = $saver;
    }
}
