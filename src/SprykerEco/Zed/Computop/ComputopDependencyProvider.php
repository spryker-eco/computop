<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use SprykerEco\Zed\Computop\Dependency\ComputopToStoreBridge;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToCalculationFacadeBridge;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeBridge;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMessengerFacadeBridge;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMoneyFacadeBridge;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsFacadeBridge;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToRefundFacadeBridge;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToSalesFacadeBridge;

class ComputopDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_COMPUTOP_API = 'SERVICE_COMPUTOP_API';

    /**
     * @var string
     */
    public const FACADE_OMS = 'FACADE_OMS';

    /**
     * @var string
     */
    public const FACADE_MONEY = 'FACADE_MONEY';

    /**
     * @var string
     */
    public const FACADE_SALES = 'FACADE_SALES';

    /**
     * @var string
     */
    public const FACADE_CALCULATION = 'FACADE_CALCULATION';

    /**
     * @var string
     */
    public const FACADE_FLASH_MESSENGER = 'FACADE_FLASH_MESSENGER';

    /**
     * @var string
     */
    public const FACADE_COMPUTOP_API = 'FACADE_COMPUTOP_API';

    /**
     * @var string
     */
    public const FACADE_REFUND = 'FACADE_REFUND';

    /**
     * @var string
     */
    public const STORE = 'STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container->set(static::FACADE_SALES, function (Container $container) {
            return new ComputopToSalesFacadeBridge($container->getLocator()->sales()->facade());
        });

        $container->set(static::FACADE_CALCULATION, function (Container $container) {
            return new ComputopToCalculationFacadeBridge($container->getLocator()->calculation()->facade());
        });

        $container = $this->addRefundFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container->set(static::SERVICE_COMPUTOP_API, function () use ($container) {
            return $container->getLocator()->computopApi()->service();
        });

        $container->set(static::FACADE_OMS, function () use ($container) {
            return new ComputopToOmsFacadeBridge($container->getLocator()->oms()->facade());
        });

        $container->set(static::STORE, function () {
            return new ComputopToStoreBridge(Store::getInstance());
        });

        $container->set(static::FACADE_FLASH_MESSENGER, function (Container $container) {
            return new ComputopToMessengerFacadeBridge($container->getLocator()->messenger()->facade());
        });

        $container->set(static::FACADE_MONEY, function (Container $container) {
            return new ComputopToMoneyFacadeBridge($container->getLocator()->money()->facade());
        });

        $container->set(static::FACADE_COMPUTOP_API, function (Container $container) {
            return new ComputopToComputopApiFacadeBridge($container->getLocator()->computopApi()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRefundFacade(Container $container): Container
    {
        $container->set(static::FACADE_REFUND, function (Container $container) {
            return new ComputopToRefundFacadeBridge($container->getLocator()->refund()->facade());
        });

        return $container;
    }
}
