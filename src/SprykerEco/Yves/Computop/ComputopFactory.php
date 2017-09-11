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
use SprykerEco\Yves\Computop\Form\CreditCardSubForm;
use SprykerEco\Yves\Computop\Form\DataProvider\CreditCardFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\DirectDebitFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\PayPalFormDataProvider;
use SprykerEco\Yves\Computop\Form\DirectDebitSubForm;
use SprykerEco\Yves\Computop\Form\PayPalSubForm;
use SprykerEco\Yves\Computop\Handler\ComputopCreditCardPaymentHandler;
use SprykerEco\Yves\Computop\Handler\ComputopDirectDebitPaymentHandler;
use SprykerEco\Yves\Computop\Handler\ComputopPayPalPaymentHandler;
use SprykerEco\Yves\Computop\Mapper\Order\CreditCardMapper;
use SprykerEco\Yves\Computop\Mapper\Order\DirectDebitMapper;
use SprykerEco\Yves\Computop\Mapper\Order\PayPalMapper;

class ComputopFactory extends AbstractFactory
{

    /**
     * @return \SprykerEco\Yves\Computop\Form\CreditCardSubForm
     */
    public function createCreditCardForm()
    {
        return new CreditCardSubForm();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Form\PayPalSubForm
     */
    public function createPayPalForm()
    {
        return new PayPalSubForm();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Form\DirectDebitSubForm
     */
    public function createDirectDebitForm()
    {
        return new DirectDebitSubForm();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Form\DataProvider\CreditCardFormDataProvider
     */
    public function createCreditCardFormDataProvider()
    {
        return new CreditCardFormDataProvider($this->getQuoteClient(), $this->createOrderCreditCardMapper());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Form\DataProvider\PayPalFormDataProvider
     */
    public function createPayPalFormDataProvider()
    {
        return new PayPalFormDataProvider($this->getQuoteClient(), $this->createOrderPayPalMapper());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Form\DataProvider\DirectDebitFormDataProvider
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

}
