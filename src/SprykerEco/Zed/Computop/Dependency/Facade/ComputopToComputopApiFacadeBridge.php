<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Dependency\Facade;

class ComputopToComputopApiFacadeBridge implements ComputopToComputopApiFacadeInterface
{
    /**
     * @var \SprykerEco\Zed\ComputopApi\Business\ComputopApiFacadeInterface
     */
    protected $computopApiFacade;

    /**
     * @param \SprykerEco\Zed\ComputopApi\Business\ComputopApiFacadeInterface $computopApiFacade
     */
    public function __construct($computopApiFacade)
    {
        $this->computopApiFacade = $computopApiFacade;
    }
}
