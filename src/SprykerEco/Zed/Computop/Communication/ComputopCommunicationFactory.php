<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerEco\Zed\Computop\ComputopDependencyProvider;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToCalculationFacadeInterface;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToRefundFacadeInterface;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToSalesFacadeInterface;

/**
 * @method \SprykerEco\Zed\Computop\ComputopConfig getConfig()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface getQueryContainer()
 * @method \SprykerEco\Zed\Computop\Business\ComputopFacadeInterface getFacade()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopRepositoryInterface getRepository()
 */
class ComputopCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToSalesFacadeInterface
     */
    public function getSalesFacade(): ComputopToSalesFacadeInterface
    {
        return $this->getProvidedDependency(
            ComputopDependencyProvider::FACADE_SALES,
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToCalculationFacadeInterface
     */
    public function getCalculationFacade(): ComputopToCalculationFacadeInterface
    {
        return $this->getProvidedDependency(
            ComputopDependencyProvider::FACADE_CALCULATION,
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToRefundFacadeInterface
     */
    public function getRefundFacade(): ComputopToRefundFacadeInterface
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::FACADE_REFUND);
    }
}
