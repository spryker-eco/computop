<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerEco\Zed\Computop\ComputopDependencyProvider;

/**
 * @method \SprykerEco\Zed\Computop\ComputopConfig getConfig()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface getQueryContainer()
 */
class ComputopCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToSalesInterface
     */
    public function getSalesFacade()
    {
        return $this->getProvidedDependency(
            ComputopDependencyProvider::FACADE_SALES
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToCalculationInterface
     */
    public function getCalculationFacade()
    {
        return $this->getProvidedDependency(
            ComputopDependencyProvider::FACADE_CALCULATION
        );
    }

    /**
     * @return \Spryker\Zed\Messenger\Business\MessengerFacadeInterface
     */
    public function getFlashMessengerFacade()
    {
        return $this->getProvidedDependency(
            ComputopDependencyProvider::FACADE_FLASH_MESSENGER
        );
    }

    /**
     * @return \SprykerEco\Service\Computop\ComputopServiceInterface
     */
    public function getComputopService()
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::COMPUTOP_SERVICE);
    }

    /**
     * @param integer $idSalesOrder
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface
     */
    public function getSavedComputopEntity($idSalesOrder)
    {
        return $this
            ->getQueryContainer()
            ->queryPaymentByOrderId($idSalesOrder)
            ->findOne();
    }
}
