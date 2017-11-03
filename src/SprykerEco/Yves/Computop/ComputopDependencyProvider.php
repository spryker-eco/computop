<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteBridge;

class ComputopDependencyProvider extends AbstractBundleDependencyProvider
{
    const COMPUTOP_CLIENT = 'COMPUTOP_CLIENT';
    const COMPUTOP_SERVICE = 'COMPUTOP_SERVICE';
    const CLIENT_QUOTE = 'CLIENT_QUOTE';
    const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container[self::COMPUTOP_CLIENT] = function (Container $container) {
            return $container->getLocator()->computop()->client();
        };

        $container[self::COMPUTOP_SERVICE] = function () use ($container) {
            return $container->getLocator()->computop()->service();
        };

        $container[self::CLIENT_QUOTE] = function () use ($container) {
            return new ComputopToQuoteBridge($container->getLocator()->quote()->client());
        };

        $container[self::PLUGIN_APPLICATION] = function () {
            $pimplePlugin = new Pimple();

            return $pimplePlugin->getApplication();
        };

        return $container;
    }
}
