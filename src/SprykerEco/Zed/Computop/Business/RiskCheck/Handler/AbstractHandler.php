<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\RiskCheck\Handler;

use SprykerEco\Zed\Computop\Business\RiskCheck\Saver\RiskCheckSaverInterface;
use SprykerEco\Zed\Computop\ComputopConfig;
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
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeInterface $computopApiFacade
     * @param \SprykerEco\Zed\Computop\Business\RiskCheck\Saver\RiskCheckSaverInterface $saver
     * @param \SprykerEco\Zed\Computop\ComputopConfig $config
     */
    public function __construct(
        ComputopToComputopApiFacadeInterface $computopApiFacade,
        RiskCheckSaverInterface $saver,
        ComputopConfig $config
    ) {
        $this->computopApiFacade = $computopApiFacade;
        $this->saver = $saver;
        $this->config = $config;
    }
}
