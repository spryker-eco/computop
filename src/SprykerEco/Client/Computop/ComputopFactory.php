<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Computop;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Shipment\ShipmentClientInterface;
use SprykerEco\Client\Computop\Zed\ComputopStub;

/**
 * @method \SprykerEco\Client\Computop\ComputopConfig getConfig()
 */
class ComputopFactory extends AbstractFactory
{
    /**
     * @return \SprykerEco\Client\Computop\Zed\ComputopStubInterface
     */
    public function createZedStub()
    {
        return new ComputopStub(
            $this->getZedRequestClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected function getZedRequestClient()
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\Shipment\ShipmentClientInterface
     */
    public function getShipmentClient(): ShipmentClientInterface
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::CLIENT_SHIPMENT);
    }
}
