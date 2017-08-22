<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerEco\Yves\Computop\Converter\OrderCreditCardConverter;
use SprykerEco\Yves\Computop\Form\CreditCardSubForm;
use SprykerEco\Yves\Computop\Form\DataProvider\CreditCardFormDataProvider;
use SprykerEco\Yves\Computop\Handler\ComputopPaymentHandler;
use SprykerEco\Yves\Computop\Mapper\CreditCard\OrderCreditCardMapper;

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
     * @return \SprykerEco\Yves\Computop\Form\DataProvider\CreditCardFormDataProvider
     */
    public function createCreditCardFormDataProvider()
    {
        return new CreditCardFormDataProvider($this->getQuoteClient(), $this->createOrderCreditCardMapper());
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
     * @return \SprykerEco\Yves\Computop\Handler\ComputopPaymentHandler
     */
    public function createPaymentHandler()
    {
        return new ComputopPaymentHandler();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    public function createOrderCreditCardConverter()
    {
        return new OrderCreditCardConverter();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Mapper\CreditCard\CreditCardMapperInterface
     */
    public function createOrderCreditCardMapper()
    {
        return new OrderCreditCardMapper($this->getComputopService(), $this->getApplication());
    }

}
