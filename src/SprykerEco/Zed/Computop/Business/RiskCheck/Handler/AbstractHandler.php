<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\RiskCheck\Handler;

use SprykerEco\Zed\Computop\Business\Api\Request\RiskCheck\RiskCheckRequestInterface;
use SprykerEco\Zed\Computop\Business\RiskCheck\Saver\RiskCheckSaverInterface;

abstract class AbstractHandler implements HandlerInterface
{
    /**
     * @var \SprykerEco\Zed\Computop\Business\Api\Request\RiskCheck\RiskCheckRequestInterface
     */
    protected $request;

    /**
     * @var \SprykerEco\Zed\Computop\Business\RiskCheck\Saver\RiskCheckSaverInterface
     */
    protected $saver;

    /**
     * @param \SprykerEco\Zed\Computop\Business\Api\Request\RiskCheck\RiskCheckRequestInterface $request
     * @param \SprykerEco\Zed\Computop\Business\RiskCheck\Saver\RiskCheckSaverInterface $saver
     */
    public function __construct(
        RiskCheckRequestInterface $request,
        RiskCheckSaverInterface $saver
    ) {
        $this->request = $request;
        $this->saver = $saver;
    }
}
