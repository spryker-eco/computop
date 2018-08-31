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
     * @return \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToSalesFacadeInterface
     */
    public function getSalesFacade()
    {
        return $this->getProvidedDependency(
            ComputopDependencyProvider::FACADE_SALES
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToCalculationFacadeInterface
     */
    public function getCalculationFacade()
    {
        return $this->getProvidedDependency(
            ComputopDependencyProvider::FACADE_CALCULATION
        );
    }
}
