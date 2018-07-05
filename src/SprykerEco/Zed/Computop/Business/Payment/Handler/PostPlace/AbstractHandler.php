<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace;

use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\SaverInterface;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeInterface;

abstract class AbstractHandler implements HandlerInterface
{
    /**
     * @var \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeInterface
     */
    protected $computopApiFacade;

    /**
     * @var \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\SaverInterface
     */
    protected $saver;

    /**
     * @param \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeInterface $computopApiFacade
     * @param \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\SaverInterface $saver
     */
    public function __construct(
        ComputopToComputopApiFacadeInterface $computopApiFacade,
        SaverInterface $saver
    ) {
        $this->computopApiFacade = $computopApiFacade;
        $this->saver = $saver;
    }
}
