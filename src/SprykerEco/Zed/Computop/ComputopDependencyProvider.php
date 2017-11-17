<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use SprykerEco\Zed\Computop\Dependency\Client\ComputopToQuoteClientBridge;
use SprykerEco\Zed\Computop\Dependency\ComputopToStoreBridge;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToCalculationFacadeBridge;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMessengerFacadeBridge;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsFacadeBridge;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToSalesFacadeBridge;

class ComputopDependencyProvider extends AbstractBundleDependencyProvider
{
    const SERVICE_COMPUTOP = 'SERVICE_COMPUTOP';
    const FACADE_OMS = 'FACADE_OMS';
    const FACADE_SALES = 'FACADE_SALES';
    const FACADE_CALCULATION = 'FACADE_CALCULATION';
    const FACADE_FLASH_MESSENGER = 'FACADE_FLASH_MESSENGER';
    const STORE = 'STORE';
    const CLIENT_QUOTE = 'CLIENT_QUOTE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container[self::FACADE_SALES] = function (Container $container) {
            return new ComputopToSalesFacadeBridge($container->getLocator()->sales()->facade());
        };

        $container[self::FACADE_CALCULATION] = function (Container $container) {
            return new ComputopToCalculationFacadeBridge($container->getLocator()->calculation()->facade());
        };

        $container[self::SERVICE_COMPUTOP] = function () use ($container) {
            return $container->getLocator()->computop()->service();
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

        $container[self::SERVICE_COMPUTOP] = function () use ($container) {
            return $container->getLocator()->computop()->service();
        };

        $container[self::FACADE_OMS] = function () use ($container) {
            return new ComputopToOmsFacadeBridge($container->getLocator()->oms()->facade());
        };

        $container[self::STORE] = function () {
            return new ComputopToStoreBridge(Store::getInstance());
        };

        $container[self::FACADE_FLASH_MESSENGER] = function (Container $container) {
            return new ComputopToMessengerFacadeBridge($container->getLocator()->messenger()->facade());
        };

        $container[self::CLIENT_QUOTE] = function () use ($container) {
            return new ComputopToQuoteClientBridge($container->getLocator()->quote()->client());
        };

        return $container;
    }
}
