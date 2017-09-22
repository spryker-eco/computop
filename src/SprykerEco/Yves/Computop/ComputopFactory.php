<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerEco\Yves\Computop\Converter\OrderCreditCardConverter;
use SprykerEco\Yves\Computop\Converter\OrderDirectDebitConverter;
use SprykerEco\Yves\Computop\Converter\OrderPayPalConverter;
use SprykerEco\Yves\Computop\Converter\OrderSofortConverter;
use SprykerEco\Yves\Computop\Form\CreditCardSubForm;
use SprykerEco\Yves\Computop\Form\DataProvider\CreditCardFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\DirectDebitFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\PayPalFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\SofortFormDataProvider;
use SprykerEco\Yves\Computop\Form\DirectDebitSubForm;
use SprykerEco\Yves\Computop\Form\PayPalSubForm;
use SprykerEco\Yves\Computop\Form\SofortSubForm;
use SprykerEco\Yves\Computop\Handler\ComputopCreditCardPaymentHandler;
use SprykerEco\Yves\Computop\Handler\ComputopDirectDebitPaymentHandler;
use SprykerEco\Yves\Computop\Handler\ComputopPaymentHandler;
use SprykerEco\Yves\Computop\Handler\ComputopPayPalPaymentHandler;
use SprykerEco\Yves\Computop\Handler\ComputopSofortPaymentHandler;
use SprykerEco\Yves\Computop\Mapper\Order\CreditCardMapper;
use SprykerEco\Yves\Computop\Mapper\Order\DirectDebitMapper;
use SprykerEco\Yves\Computop\Mapper\Order\PayPalMapper;
use SprykerEco\Yves\Computop\Mapper\Order\SofortMapper;

class ComputopFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createCreditCardForm()
    {
        return new CreditCardSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createPayPalForm()
    {
        return new PayPalSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createSofortForm()
    {
        return new SofortSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createDirectDebitForm()
    {
        return new DirectDebitSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createCreditCardFormDataProvider()
    {
        return new CreditCardFormDataProvider($this->getQuoteClient(), $this->createOrderCreditCardMapper());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPayPalFormDataProvider()
    {
        return new PayPalFormDataProvider($this->getQuoteClient(), $this->createOrderPayPalMapper());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createSofortFormDataProvider()
    {
        return new SofortFormDataProvider($this->getQuoteClient(), $this->createOrderSofortMapper());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createDirectDebitFormDataProvider()
    {
        return new DirectDebitFormDataProvider($this->getQuoteClient(), $this->createOrderDirectDebitMapper());
    }

    /**
     * @return \SprykerEco\Client\Computop\ComputopClient
     */
    public function getComputopClient()
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::COMPUTOP_CLIENT);
    }

    /**
     * @return \SprykerEco\Yves\Computop\Dependency\Client\ComputopToComputopServiceInterface
     */
    public function getComputopService()
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::COMPUTOP_SERVICE);
    }

    /**
     * @return \Silex\Application
     */
    public function getApplication()
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteInterface
     */
    public function getQuoteClient()
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\ComputopPaymentHandlerInterface
     */
    public function createCreditCardPaymentHandler()
    {
        return new ComputopCreditCardPaymentHandler();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\ComputopPaymentHandlerInterface
     */
    public function createPayPalPaymentHandler()
    {
        return new ComputopPayPalPaymentHandler();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\ComputopPaymentHandlerInterface
     */
    public function createDirectDebitPaymentHandler()
    {
        return new ComputopDirectDebitPaymentHandler();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\ComputopPaymentHandlerInterface
     */
    public function createSofortPaymentHandler()
    {
        return new ComputopSofortPaymentHandler();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    public function createOrderCreditCardConverter()
    {
        return new OrderCreditCardConverter();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    public function createOrderPayPalConverter()
    {
        return new OrderPayPalConverter();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    public function createOrderDirectDebitConverter()
    {
        return new OrderDirectDebitConverter();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    public function createOrderSofortConverter()
    {
        return new OrderSofortConverter();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Mapper\Order\MapperInterface
     */
    public function createOrderCreditCardMapper()
    {
        return new CreditCardMapper($this->getComputopService(), $this->getApplication());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Mapper\Order\MapperInterface
     */
    public function createOrderPayPalMapper()
    {
        return new PayPalMapper($this->getComputopService(), $this->getApplication());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Mapper\Order\MapperInterface
     */
    public function createOrderDirectDebitMapper()
    {
        return new DirectDebitMapper($this->getComputopService(), $this->getApplication());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Mapper\Order\MapperInterface
     */
    public function createOrderSofortMapper()
    {
        return new SofortMapper($this->getComputopService(), $this->getApplication());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\ComputopPaymentHandler
     */
    public function createComputopPaymentHandler()
    {
        return new ComputopPaymentHandler();
    }

}
