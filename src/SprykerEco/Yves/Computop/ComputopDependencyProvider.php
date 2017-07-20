<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteBridge;

class ComputopDependencyProvider extends AbstractBundleDependencyProvider
{

    const CLIENT_QUOTE = 'quote_client';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
//        $container[self::CLIENT_QUOTE] = function () use ($container) {
//            return new ComputopToQuoteBridge($container->getLocator()->quote()->client());
//        };

        return $container;
    }

}
