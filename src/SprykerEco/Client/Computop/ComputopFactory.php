<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Computop;

use Spryker\Client\Kernel\AbstractFactory;
use SprykerEco\Client\Computop\Zed\ComputopStub;

class ComputopFactory extends AbstractFactory
{
    /**
     * @return \SprykerEco\Client\Computop\Zed\ComputopStub
     */
    public function createZedStub()
    {
        return new ComputopStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected function getZedRequestClient()
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
