<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToCalculationClientBridge;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteClientBridge;
use SprykerEco\Yves\Computop\Dependency\ComputopToStoreBridge;

class ComputopDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_COMPUTOP = 'CLIENT_COMPUTOP';
    public const SERVICE_COMPUTOP_API = 'SERVICE_COMPUTOP_API';
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';
    public const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';
    public const STORE = 'STORE';
    public const CLIENT_CALCULATION = 'CLIENT_CALCULATION';

    /**
     * @uses \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     */
    public const SERVICE_ROUTER = 'routers';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container[static::CLIENT_COMPUTOP] = function (Container $container) {
            return $container->getLocator()->computop()->client();
        };

        $container[static::SERVICE_COMPUTOP_API] = function () use ($container) {
            return $container->getLocator()->computopApi()->service();
        };

        $container[static::CLIENT_QUOTE] = function () use ($container) {
            return new ComputopToQuoteClientBridge($container->getLocator()->quote()->client());
        };

        $container[static::CLIENT_CALCULATION] = function () use ($container) {
            return new ComputopToCalculationClientBridge($container->getLocator()->calculation()->client());
        };

        $container[static::PLUGIN_APPLICATION] = function () {
            $pimplePlugin = new Pimple();

            return $pimplePlugin->getApplication();
        };

        $container[static::STORE] = function () {
            return new ComputopToStoreBridge(Store::getInstance());
        };

        $container = $this->addRouter($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRouter(Container $container): Container
    {
        $container->set(static::SERVICE_ROUTER, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_ROUTER);
        });

        return $container;
    }
}
