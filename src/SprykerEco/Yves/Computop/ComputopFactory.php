<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerEco\Yves\Computop\Converter\OrderCreditCardConverter;
use SprykerEco\Yves\Computop\Converter\OrderDirectDebitConverter;
use SprykerEco\Yves\Computop\Converter\OrderIdealConverter;
use SprykerEco\Yves\Computop\Converter\OrderPaydirektConverter;
use SprykerEco\Yves\Computop\Converter\OrderPayPalConverter;
use SprykerEco\Yves\Computop\Converter\OrderSofortConverter;
use SprykerEco\Yves\Computop\Form\CreditCardSubForm;
use SprykerEco\Yves\Computop\Form\DataProvider\CreditCardFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\DirectDebitFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\IdealFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\PaydirektFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\PayPalFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\SofortFormDataProvider;
use SprykerEco\Yves\Computop\Form\DirectDebitSubForm;
use SprykerEco\Yves\Computop\Form\IdealSubForm;
use SprykerEco\Yves\Computop\Form\PaydirektSubForm;
use SprykerEco\Yves\Computop\Form\PayPalSubForm;
use SprykerEco\Yves\Computop\Form\SofortSubForm;
use SprykerEco\Yves\Computop\Handler\ComputopPaymentHandler;
use SprykerEco\Yves\Computop\Handler\PostPlace\ComputopIdealPaymentHandler;
use SprykerEco\Yves\Computop\Handler\PostPlace\ComputopPaydirektPaymentHandler;
use SprykerEco\Yves\Computop\Handler\PostPlace\ComputopSofortPaymentHandler;
use SprykerEco\Yves\Computop\Handler\PrePlace\ComputopCreditCardPaymentHandler;
use SprykerEco\Yves\Computop\Handler\PrePlace\ComputopDirectDebitPaymentHandler;
use SprykerEco\Yves\Computop\Handler\PrePlace\ComputopPayPalPaymentHandler;
use SprykerEco\Yves\Computop\Mapper\Order\PostPlace\IdealMapper;
use SprykerEco\Yves\Computop\Mapper\Order\PostPlace\PaydirektMapper;
use SprykerEco\Yves\Computop\Mapper\Order\PostPlace\SofortMapper;
use SprykerEco\Yves\Computop\Mapper\Order\PrePlace\CreditCardMapper;
use SprykerEco\Yves\Computop\Mapper\Order\PrePlace\DirectDebitMapper;
use SprykerEco\Yves\Computop\Mapper\Order\PrePlace\PayPalMapper;

/**
 * @method \SprykerEco\Yves\Computop\ComputopConfig getConfig()
 */
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
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createIdealForm()
    {
        return new IdealSubForm();
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
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createIdealFormDataProvider()
    {
        return new IdealFormDataProvider($this->getQuoteClient(), $this->createOrderIdealMapper());
    }

    /**
     * @return \SprykerEco\Client\Computop\ComputopClientInterface
     */
    public function getComputopClient()
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::COMPUTOP_CLIENT);
    }

    /**
     * @return \SprykerEco\Service\Computop\ComputopServiceInterface
     */
    public function getComputopService()
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::COMPUTOP_SERVICE);
    }

    /**
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
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
     * @return \SprykerEco\Yves\Computop\Handler\PostPlace\ComputopPaymentHandlerInterface
     */
    public function createPaydirektPaymentHandler()
    {
        return new ComputopPaydirektPaymentHandler();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\PostPlace\ComputopPaymentHandlerInterface
     */
    public function createSofortPaymentHandler()
    {
        return new ComputopSofortPaymentHandler();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\PostPlace\ComputopPaymentHandlerInterface
     */
    public function createIdealPaymentHandler()
    {
        return new ComputopIdealPaymentHandler();
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
     * @return \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    public function createOrderIdealConverter()
    {
        return new OrderIdealConverter($this->getComputopService());
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
     * @return \SprykerEco\Yves\Computop\Mapper\Order\MapperInterface
     */
    public function createOrderIdealMapper()
    {
        return new IdealMapper($this->getComputopService(), $this->getApplication());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\ComputopPaymentHandlerInterface
     */
    public function createComputopPaymentHandler()
    {
        return new ComputopPaymentHandler();
    }

    /**
     * @return \SprykerEco\Yves\Computop\ComputopConfigInterface
     */
    public function getComputopConfig()
    {
        return $this->getConfig();
    }
}
