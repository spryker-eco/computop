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
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteClientBridge;
use SprykerEco\Yves\Computop\Dependency\ComputopToStoreBridge;

class ComputopDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_COMPUTOP = 'CLIENT_COMPUTOP';
    const SERVICE_COMPUTOP = 'SERVICE_COMPUTOP';
    const CLIENT_QUOTE = 'CLIENT_QUOTE';
    const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';
    const STORE = 'STORE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container[self::CLIENT_COMPUTOP] = function (Container $container) {
            return $container->getLocator()->computop()->client();
        };

        $container[self::SERVICE_COMPUTOP] = function () use ($container) {
            return $container->getLocator()->computop()->service();
        };

        $container[self::CLIENT_QUOTE] = function () use ($container) {
            return new ComputopToQuoteClientBridge($container->getLocator()->quote()->client());
        };

        $container[self::PLUGIN_APPLICATION] = function () {
            $pimplePlugin = new Pimple();

            return $pimplePlugin->getApplication();
        };

        $container[self::STORE] = function () {
            return new ComputopToStoreBridge(Store::getInstance());
        };

        return $container;
    }
}
