<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Dependency\Injector;

use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Shared\Kernel\Dependency\Injector\DependencyInjectorInterface;
use Spryker\Yves\Checkout\CheckoutDependencyProvider;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Yves\Computop\Plugin\ComputopPaymentHandlerPlugin;
use SprykerEco\Yves\Computop\Plugin\CreditCardSubFormPlugin;
use SprykerEco\Yves\Computop\Plugin\DirectDebitSubFormPlugin;
use SprykerEco\Yves\Computop\Plugin\PaydirektSubFormPlugin;
use SprykerEco\Yves\Computop\Plugin\PayPalSubFormPlugin;
use SprykerEco\Yves\Computop\Plugin\SofortSubFormPlugin;

class CheckoutDependencyInjector implements DependencyInjectorInterface
{
    /**
     * @param \Spryker\Shared\Kernel\ContainerInterface|\Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface|\Spryker\Yves\Kernel\Container
     */
    public function inject(ContainerInterface $container)
    {
        $container = $this->injectPaymentSubForms($container);
        $container = $this->injectPaymentMethodHandler($container);

        return $container;
    }

    /**
     * @param \Spryker\Shared\Kernel\ContainerInterface $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface
     */
    protected function injectPaymentSubForms(ContainerInterface $container)
    {
        $container->extend(CheckoutDependencyProvider::PAYMENT_SUB_FORMS, function (SubFormPluginCollection $paymentSubForms) {
            $paymentSubForms->add(new CreditCardSubFormPlugin());
            $paymentSubForms->add(new PayPalSubFormPlugin());
            $paymentSubForms->add(new DirectDebitSubFormPlugin());
            $paymentSubForms->add(new SofortSubFormPlugin());
            $paymentSubForms->add(new PaydirektSubFormPlugin());

            return $paymentSubForms;
        });

        return $container;
    }

    /**
     * @param \Spryker\Shared\Kernel\ContainerInterface $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface
     */
    protected function injectPaymentMethodHandler(ContainerInterface $container)
    {
        $container->extend(CheckoutDependencyProvider::PAYMENT_METHOD_HANDLER, function (StepHandlerPluginCollection $paymentMethodHandler) {
            $paymentHandlerPlugin = new ComputopPaymentHandlerPlugin();
            $paymentMethodHandler->add($paymentHandlerPlugin, ComputopConfig::PAYMENT_METHOD_SOFORT);
            $paymentMethodHandler->add($paymentHandlerPlugin, ComputopConfig::PAYMENT_METHOD_PAYDIREKT);

            return $paymentMethodHandler;
        });

        return $container;
    }
}
