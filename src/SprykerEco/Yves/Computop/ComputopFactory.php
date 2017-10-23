<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerEco\Yves\Computop\Converter\OrderCreditCardConverter;
use SprykerEco\Yves\Computop\Converter\OrderDirectDebitConverter;
use SprykerEco\Yves\Computop\Converter\OrderPaydirektConverter;
use SprykerEco\Yves\Computop\Converter\OrderPayPalConverter;
use SprykerEco\Yves\Computop\Converter\OrderSofortConverter;
use SprykerEco\Yves\Computop\Form\CreditCardSubForm;
use SprykerEco\Yves\Computop\Form\DataProvider\CreditCardFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\DirectDebitFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\PaydirektFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\PayPalFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\SofortFormDataProvider;
use SprykerEco\Yves\Computop\Form\DirectDebitSubForm;
use SprykerEco\Yves\Computop\Form\PaydirektSubForm;
use SprykerEco\Yves\Computop\Form\PayPalSubForm;
use SprykerEco\Yves\Computop\Form\SofortSubForm;
use SprykerEco\Yves\Computop\Handler\ComputopPaymentHandler;
use SprykerEco\Yves\Computop\Handler\PrePlace\ComputopCreditCardPaymentHandler;
use SprykerEco\Yves\Computop\Handler\PrePlace\ComputopDirectDebitPaymentHandler;
use SprykerEco\Yves\Computop\Handler\PrePlace\ComputopPaydirektPaymentHandler;
use SprykerEco\Yves\Computop\Handler\PrePlace\ComputopPayPalPaymentHandler;
use SprykerEco\Yves\Computop\Handler\PrePlace\ComputopSofortPaymentHandler;
use SprykerEco\Yves\Computop\Mapper\Order\PostPlace\SofortMapper;
use SprykerEco\Yves\Computop\Mapper\Order\PrePlace\CreditCardMapper;
use SprykerEco\Yves\Computop\Mapper\Order\PrePlace\DirectDebitMapper;
use SprykerEco\Yves\Computop\Mapper\Order\PrePlace\PaydirektMapper;
use SprykerEco\Yves\Computop\Mapper\Order\PrePlace\PayPalMapper;

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
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createPaydirektForm()
    {
        return new PaydirektSubForm();
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
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPaydirektFormDataProvider()
    {
        return new PaydirektFormDataProvider($this->getQuoteClient(), $this->createOrderPaydirektMapper());
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
     * @return \SprykerEco\Yves\Computop\Handler\PrePlace\ComputopPaymentHandlerInterface
     */
    public function createCreditCardPaymentHandler()
    {
        return new ComputopCreditCardPaymentHandler();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\PrePlace\ComputopPaymentHandlerInterface
     */
    public function createPayPalPaymentHandler()
    {
        return new ComputopPayPalPaymentHandler();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\PrePlace\ComputopPaymentHandlerInterface
     */
    public function createDirectDebitPaymentHandler()
    {
        return new ComputopDirectDebitPaymentHandler();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\PrePlace\ComputopPaymentHandlerInterface
     */
    public function createPaydirektPaymentHandler()
    {
        return new ComputopPaydirektPaymentHandler();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\PrePlace\ComputopPaymentHandlerInterface
     */
    public function createSofortPaymentHandler()
    {
        return new ComputopSofortPaymentHandler($this->getComputopService());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    public function createOrderCreditCardConverter()
    {
        return new OrderCreditCardConverter($this->getComputopService());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    public function createOrderPayPalConverter()
    {
        return new OrderPayPalConverter($this->getComputopService());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    public function createOrderDirectDebitConverter()
    {
        return new OrderDirectDebitConverter($this->getComputopService());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    public function createOrderPaydirektConverter()
    {
        return new OrderPaydirektConverter($this->getComputopService());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    public function createOrderSofortConverter()
    {
        return new OrderSofortConverter($this->getComputopService());
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
     * @return \SprykerEco\Yves\Computop\Mapper\Order\MapperInterface
     */
    public function createOrderPaydirektMapper()
    {
        return new PaydirektMapper($this->getComputopService(), $this->getApplication());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\ComputopPaymentHandler
     */
    public function createComputopPaymentHandler()
    {
        return new ComputopPaymentHandler();
    }
}
