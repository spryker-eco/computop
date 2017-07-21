<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToComputopServiceBridge;

class ComputopDependencyProvider extends AbstractBundleDependencyProvider
{

    const COMPUTOP_SERVICE = 'computop_service';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container[self::COMPUTOP_SERVICE] = function () use ($container) {
            return new ComputopToComputopServiceBridge($container->getLocator()->computop()->service());
        };

        return $container;
    }

}
