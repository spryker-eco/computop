<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToCalculationBridge;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceBridge;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMessengerBridge;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToSalesBridge;

class ComputopDependencyProvider extends AbstractBundleDependencyProvider
{

    const COMPUTOP_SERVICE = 'computop_service';
    const FACADE_SALES = 'sales facade';
    const FACADE_CALCULATION = 'calculation facade';
    const FACADE_FLASH_MESSENGER = 'flash messenger facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container[self::FACADE_SALES] = function (Container $container) {
            return new ComputopToSalesBridge($container->getLocator()->sales()->facade());
        };

        $container[self::FACADE_CALCULATION] = function (Container $container) {
            return new ComputopToCalculationBridge($container->getLocator()->calculation()->facade());
        };

        $container[self::FACADE_FLASH_MESSENGER] = function (Container $container) {
            return new ComputopToMessengerBridge($container->getLocator()->messenger()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container[self::COMPUTOP_SERVICE] = function () use ($container) {
            return new ComputopToComputopServiceBridge($container->getLocator()->computop()->service());
        };

        return $container;
    }

}
