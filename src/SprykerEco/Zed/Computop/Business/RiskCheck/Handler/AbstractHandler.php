<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\RiskCheck\Handler;

use SprykerEco\Zed\Computop\Business\RiskCheck\Saver\RiskCheckSaverInterface;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeInterface;

abstract class AbstractHandler implements HandlerInterface
{
    /**
     * @var \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeInterface
     */
    protected $computopApiFacade;

    /**
     * @var \SprykerEco\Zed\Computop\Business\RiskCheck\Saver\RiskCheckSaverInterface
     */
    protected $saver;

    /**
     * @param \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeInterface $computopApiFacade
     * @param \SprykerEco\Zed\Computop\Business\RiskCheck\Saver\RiskCheckSaverInterface $saver
     */
    public function __construct(
        ComputopToComputopApiFacadeInterface $computopApiFacade,
        RiskCheckSaverInterface $saver
    ) {
        $this->computopApiFacade = $computopApiFacade;
        $this->saver = $saver;
    }
}
