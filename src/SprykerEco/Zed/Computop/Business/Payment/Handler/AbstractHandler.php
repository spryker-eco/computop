<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler;

use SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\SaverInterface;

abstract class AbstractHandler implements HandlerInterface
{
    /**
     * @var \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface
     */
    protected $request;

    /**
     * @var \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\SaverInterface
     */
    protected $saver;

    /**
     * @param \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface $request
     * @param \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\SaverInterface $saver
     */
    public function __construct(
        RequestInterface $request,
        SaverInterface $saver
    ) {
        $this->request = $request;
        $this->saver = $saver;
    }
}
