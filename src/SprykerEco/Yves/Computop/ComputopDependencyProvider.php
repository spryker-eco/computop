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
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToCountryClientBridge;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteClientBridge;
use SprykerEco\Yves\Computop\Dependency\ComputopToStoreBridge;
use SprykerEco\Yves\Computop\Dependency\Service\ComputopToUtilEncodingServiceBridge;

class ComputopDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_COMPUTOP = 'CLIENT_COMPUTOP';
    public const SERVICE_COMPUTOP_API = 'SERVICE_COMPUTOP_API';
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';
    public const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';
    public const STORE = 'STORE';
    public const CLIENT_CALCULATION = 'CLIENT_CALCULATION';
    public const CLIENT_COUNTRY = 'CLIENT_COUNTRY';

    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @uses \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     */
    public const SERVICE_ROUTER = 'routers';

    /**
     * @uses \Spryker\Yves\Http\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container->set(static::CLIENT_COMPUTOP, function (Container $container) {
            return $container->getLocator()->computop()->client();
        });

        $container->set(static::SERVICE_COMPUTOP_API, function () use ($container) {
            return $container->getLocator()->computopApi()->service();
        });

        $container->set(static::CLIENT_QUOTE, function () use ($container) {
            return new ComputopToQuoteClientBridge($container->getLocator()->quote()->client());
        });

        $container->set(static::CLIENT_CALCULATION, function () use ($container) {
            return new ComputopToCalculationClientBridge($container->getLocator()->calculation()->client());
        });

        $container->set(static::PLUGIN_APPLICATION, function () {
            $pimplePlugin = new Pimple();

            return $pimplePlugin->getApplication();
        });

        $container->set(static::STORE, function () {
            return new ComputopToStoreBridge(Store::getInstance());
        });

        $container = $this->addRouter($container);
        $container = $this->addRequestStack($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addCountryClient($container);

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

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRequestStack(Container $container): Container
    {
        $container->set(static::SERVICE_REQUEST_STACK, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_REQUEST_STACK);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new ComputopToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCountryClient(Container $container): Container
    {
        $container->set(static::CLIENT_COUNTRY, function (Container $container) {
            return new ComputopToCountryClientBridge($container->getLocator()->country()->client());
        });

        return $container;
    }
}
