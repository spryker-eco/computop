<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Router\Router\RouterInterface;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use SprykerEco\Client\Computop\ComputopClientInterface;
use SprykerEco\Service\ComputopApi\ComputopApiServiceInterface;
use SprykerEco\Yves\Computop\Converter\ConverterInterface;
use SprykerEco\Yves\Computop\Converter\InitCreditCardConverter;
use SprykerEco\Yves\Computop\Converter\InitDirectDebitConverter;
use SprykerEco\Yves\Computop\Converter\InitEasyCreditConverter;
use SprykerEco\Yves\Computop\Converter\InitIdealConverter;
use SprykerEco\Yves\Computop\Converter\InitPaydirektConverter;
use SprykerEco\Yves\Computop\Converter\InitPayNowConverter;
use SprykerEco\Yves\Computop\Converter\InitPayPalConverter;
use SprykerEco\Yves\Computop\Converter\InitPayPalExpressConverter;
use SprykerEco\Yves\Computop\Converter\InitSofortConverter;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToCalculationClientInterface;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToComputopApiClientInterface;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToCountryClientInterface;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteClientInterface;
use SprykerEco\Yves\Computop\Dependency\ComputopToStoreInterface;
use SprykerEco\Yves\Computop\Dependency\Plugin\PayPalExpressInitPluginInterface;
use SprykerEco\Yves\Computop\Dependency\Service\ComputopToUtilEncodingServiceInterface;
use SprykerEco\Yves\Computop\Form\CreditCardSubForm;
use SprykerEco\Yves\Computop\Form\DataProvider\CreditCardFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\DirectDebitFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\EasyCreditFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\IdealFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\PaydirektFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\PayNowFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\PayPalExpressFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\PayPalFormDataProvider;
use SprykerEco\Yves\Computop\Form\DataProvider\SofortFormDataProvider;
use SprykerEco\Yves\Computop\Form\DirectDebitSubForm;
use SprykerEco\Yves\Computop\Form\EasyCreditSubForm;
use SprykerEco\Yves\Computop\Form\IdealSubForm;
use SprykerEco\Yves\Computop\Form\PaydirektSubForm;
use SprykerEco\Yves\Computop\Form\PayNowSubForm;
use SprykerEco\Yves\Computop\Form\PayPalSubForm;
use SprykerEco\Yves\Computop\Form\SofortSubForm;
use SprykerEco\Yves\Computop\Handler\ComputopPaymentHandler;
use SprykerEco\Yves\Computop\Handler\ComputopPaymentHandlerInterface;
use SprykerEco\Yves\Computop\Handler\ComputopPrePostPaymentHandlerInterface;
use SprykerEco\Yves\Computop\Handler\ExpressCheckout\ComputopPayPalExpressCompleteAggregator;
use SprykerEco\Yves\Computop\Handler\ExpressCheckout\ComputopPayPalExpressCompleteAggregatorInterface;
use SprykerEco\Yves\Computop\Handler\ExpressCheckout\ComputopPayPalExpressInitAggregator;
use SprykerEco\Yves\Computop\Handler\ExpressCheckout\ComputopPayPalExpressInitAggregatorInterface;
use SprykerEco\Yves\Computop\Handler\ExpressCheckout\ComputopPayPalExpressPrepareAggregator;
use SprykerEco\Yves\Computop\Handler\ExpressCheckout\ComputopPayPalExpressPrepareAggregatorInterface;
use SprykerEco\Yves\Computop\Handler\PostPlace\ComputopCreditCardPaymentHandler;
use SprykerEco\Yves\Computop\Handler\PostPlace\ComputopDirectDebitPaymentHandler;
use SprykerEco\Yves\Computop\Handler\PostPlace\ComputopEasyCreditPaymentHandler;
use SprykerEco\Yves\Computop\Handler\PostPlace\ComputopIdealPaymentHandler;
use SprykerEco\Yves\Computop\Handler\PostPlace\ComputopPaydirektPaymentHandler;
use SprykerEco\Yves\Computop\Handler\PostPlace\ComputopPayNowPaymentHandler;
use SprykerEco\Yves\Computop\Handler\PostPlace\ComputopPayPalPaymentHandler;
use SprykerEco\Yves\Computop\Handler\PostPlace\ComputopSofortPaymentHandler;
use SprykerEco\Yves\Computop\Mapper\Init\MapperInterface;
use SprykerEco\Yves\Computop\Mapper\Init\PostPlace\CreditCardMapper;
use SprykerEco\Yves\Computop\Mapper\Init\PostPlace\DirectDebitMapper;
use SprykerEco\Yves\Computop\Mapper\Init\PostPlace\EasyCreditMapper;
use SprykerEco\Yves\Computop\Mapper\Init\PostPlace\IdealMapper;
use SprykerEco\Yves\Computop\Mapper\Init\PostPlace\PaydirektMapper;
use SprykerEco\Yves\Computop\Mapper\Init\PostPlace\PayNowMapper;
use SprykerEco\Yves\Computop\Mapper\Init\PostPlace\PayPalExpressMapper;
use SprykerEco\Yves\Computop\Mapper\Init\PostPlace\PayPalMapper;
use SprykerEco\Yves\Computop\Mapper\Init\PostPlace\SofortMapper;
use SprykerEco\Yves\Computop\Mapper\Init\PrePlace\PayPalExpressToQuoteMapper;
use SprykerEco\Yves\Computop\Mapper\Init\PrePlace\PayPalExpressToQuoteMapperInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @method \SprykerEco\Yves\Computop\ComputopConfig getConfig()
 * @method \SprykerEco\Client\Computop\ComputopClientInterface getClient()
 */
class ComputopFactory extends AbstractFactory
{
    /**
     * @return \SprykerEco\Yves\Computop\ComputopConfigInterface
     */
    public function getComputopConfig(): ComputopConfigInterface
    {
        return $this->getConfig();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\ComputopPaymentHandlerInterface
     */
    public function createComputopPaymentHandler(): ComputopPaymentHandlerInterface
    {
        return new ComputopPaymentHandler($this->getConfig());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createCreditCardForm(): SubFormInterface
    {
        return new CreditCardSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createPayNowForm(): SubFormInterface
    {
        return new PayNowSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createPayPalForm(): SubFormInterface
    {
        return new PayPalSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createSofortForm(): SubFormInterface
    {
        return new SofortSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createDirectDebitForm(): SubFormInterface
    {
        return new DirectDebitSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createPaydirektForm(): SubFormInterface
    {
        return new PaydirektSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createIdealForm(): SubFormInterface
    {
        return new IdealSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createEasyCreditForm(): SubFormInterface
    {
        return new EasyCreditSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createCreditCardFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new CreditCardFormDataProvider($this->getQuoteClient(), $this->createOrderCreditCardMapper());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPayNowFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new PayNowFormDataProvider($this->getQuoteClient(), $this->createOrderPayNowMapper());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPayPalFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new PayPalFormDataProvider($this->getQuoteClient(), $this->createOrderPayPalMapper());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPayPalExpressFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new PayPalExpressFormDataProvider($this->getQuoteClient(), $this->createPayPalExpressMapper());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createSofortFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new SofortFormDataProvider($this->getQuoteClient(), $this->createOrderSofortMapper());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createDirectDebitFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new DirectDebitFormDataProvider($this->getQuoteClient(), $this->createOrderDirectDebitMapper());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPaydirektFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new PaydirektFormDataProvider($this->getQuoteClient(), $this->createOrderPaydirektMapper());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createIdealFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new IdealFormDataProvider($this->getQuoteClient(), $this->createOrderIdealMapper());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createEasyCreditFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new EasyCreditFormDataProvider($this->getQuoteClient(), $this->createOrderEasyCreditMapper());
    }

    /**
     * @return \SprykerEco\Service\ComputopApi\ComputopApiServiceInterface
     */
    public function getComputopApiService(): ComputopApiServiceInterface
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::SERVICE_COMPUTOP_API);
    }

    /**
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function getApplication(): HttpKernelInterface
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteClientInterface
     */
    public function getQuoteClient(): ComputopToQuoteClientInterface
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\ComputopPrePostPaymentHandlerInterface
     */
    public function createCreditCardPaymentHandler(): ComputopPrePostPaymentHandlerInterface
    {
        return new ComputopCreditCardPaymentHandler($this->createInitCreditCardConverter(), $this->getComputopClient());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\ComputopPrePostPaymentHandlerInterface
     */
    public function createPayNowPaymentHandler(): ComputopPrePostPaymentHandlerInterface
    {
        return new ComputopPayNowPaymentHandler($this->createInitPayNowConverter(), $this->getComputopClient());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\ComputopPrePostPaymentHandlerInterface
     */
    public function createPayPalPaymentHandler(): ComputopPrePostPaymentHandlerInterface
    {
        return new ComputopPayPalPaymentHandler($this->createInitPayPalConverter(), $this->getComputopClient());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\ExpressCheckout\ComputopPayPalExpressInitAggregatorInterface
     */
    public function createComputopPayPalExpressInitAggregator(): ComputopPayPalExpressInitAggregatorInterface
    {
        return new ComputopPayPalExpressInitAggregator(
            $this->createComputopPaymentHandler(),
            $this->createInitPayPalExpressConverter(),
            $this->getQuoteClient(),
            $this->getComputopClient(),
            $this->createPayPalExpressToQuoteMapper(),
            $this->getPayPalExpressInitAggregatorPluginsStack()
        );
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\ExpressCheckout\ComputopPayPalExpressPrepareAggregatorInterface
     */
    public function createComputopPayPalExpressPrepareAggregator(): ComputopPayPalExpressPrepareAggregatorInterface
    {
        return new ComputopPayPalExpressPrepareAggregator(
            $this->getQuoteClient(),
            $this->createPayPalExpressFormDataProvider(),
            $this->getComputopApiClient(),
            $this->getComputopApiService(),
            $this->getComputopConfig()
        );
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\ExpressCheckout\ComputopPayPalExpressCompleteAggregatorInterface
     */
    public function createComputopPayPalExpressCompleteAggregator(): ComputopPayPalExpressCompleteAggregatorInterface
    {
        return new ComputopPayPalExpressCompleteAggregator(
            $this->getComputopApiClient(),
            $this->getComputopClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\ComputopPrePostPaymentHandlerInterface
     */
    public function createDirectDebitPaymentHandler(): ComputopPrePostPaymentHandlerInterface
    {
        return new ComputopDirectDebitPaymentHandler($this->createInitDirectDebitConverter(), $this->getComputopClient());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\ComputopPrePostPaymentHandlerInterface
     */
    public function createEasyCreditPaymentHandler(): ComputopPrePostPaymentHandlerInterface
    {
        return new ComputopEasyCreditPaymentHandler(
            $this->createInitEasyCreditConverter(),
            $this->getComputopClient(),
            $this->getCalculationClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\ComputopPrePostPaymentHandlerInterface
     */
    public function createPaydirektPaymentHandler(): ComputopPrePostPaymentHandlerInterface
    {
        return new ComputopPaydirektPaymentHandler($this->createInitPaydirektConverter(), $this->getComputopClient());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\ComputopPrePostPaymentHandlerInterface
     */
    public function createSofortPaymentHandler(): ComputopPrePostPaymentHandlerInterface
    {
        return new ComputopSofortPaymentHandler($this->createInitSofortConverter(), $this->getComputopClient());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Handler\ComputopPrePostPaymentHandlerInterface
     */
    public function createIdealPaymentHandler(): ComputopPrePostPaymentHandlerInterface
    {
        return new ComputopIdealPaymentHandler($this->createInitIdealConverter(), $this->getComputopClient());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    public function createInitCreditCardConverter(): ConverterInterface
    {
        return new InitCreditCardConverter($this->getComputopApiService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    public function createInitPayNowConverter(): ConverterInterface
    {
        return new InitPayNowConverter($this->getComputopApiService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    public function createInitPayPalConverter(): ConverterInterface
    {
        return new InitPayPalConverter($this->getComputopApiService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    public function createInitPayPalExpressConverter(): ConverterInterface
    {
        return new InitPayPalExpressConverter($this->getComputopApiService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    public function createInitDirectDebitConverter(): ConverterInterface
    {
        return new InitDirectDebitConverter($this->getComputopApiService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    public function createInitEasyCreditConverter(): ConverterInterface
    {
        return new InitEasyCreditConverter($this->getComputopApiService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    public function createInitPaydirektConverter(): ConverterInterface
    {
        return new InitPaydirektConverter($this->getComputopApiService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    public function createInitSofortConverter(): ConverterInterface
    {
        return new InitSofortConverter($this->getComputopApiService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    public function createInitIdealConverter(): ConverterInterface
    {
        return new InitIdealConverter($this->getComputopApiService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Client\Computop\ComputopClientInterface
     */
    public function getComputopClient(): ComputopClientInterface
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::CLIENT_COMPUTOP);
    }

    /**
     * @return \SprykerEco\Yves\Computop\Dependency\ComputopToStoreInterface
     */
    public function getStore(): ComputopToStoreInterface
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::STORE);
    }

    /**
     * @return \SprykerEco\Yves\Computop\Dependency\Client\ComputopToCalculationClientInterface
     */
    public function getCalculationClient(): ComputopToCalculationClientInterface
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::CLIENT_CALCULATION);
    }

    /**
     * @return \SprykerEco\Yves\Computop\Dependency\Client\ComputopToComputopApiClientInterface
     */
    public function getComputopApiClient(): ComputopToComputopApiClientInterface
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::CLIENT_COMPUTOP_API);
    }

    /**
     * @return \SprykerEco\Yves\Computop\Mapper\Init\MapperInterface
     */
    public function createOrderCreditCardMapper(): MapperInterface
    {
        return new CreditCardMapper(
            $this->getComputopApiService(),
            $this->getRouter(),
            $this->getStore(),
            $this->getConfig(),
            $this->getRequestStack()->getCurrentRequest(),
            $this->getUtilEncodingService(),
            $this->getCountryClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\Computop\Mapper\Init\MapperInterface
     */
    public function createOrderPayNowMapper(): MapperInterface
    {
        return new PayNowMapper(
            $this->getComputopApiService(),
            $this->getRouter(),
            $this->getStore(),
            $this->getConfig(),
            $this->getRequestStack()->getCurrentRequest(),
            $this->getUtilEncodingService(),
            $this->getCountryClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\Computop\Mapper\Init\MapperInterface
     */
    public function createOrderPayPalMapper(): MapperInterface
    {
        return new PayPalMapper(
            $this->getComputopApiService(),
            $this->getRouter(),
            $this->getStore(),
            $this->getConfig(),
            $this->getRequestStack()->getCurrentRequest(),
            $this->getUtilEncodingService(),
            $this->getCountryClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\Computop\Mapper\Init\MapperInterface
     */
    public function createPayPalExpressMapper(): MapperInterface
    {
        return new PayPalExpressMapper(
            $this->getComputopApiService(),
            $this->getRouter(),
            $this->getStore(),
            $this->getConfig(),
            $this->getRequestStack()->getCurrentRequest(),
            $this->getUtilEncodingService(),
            $this->getCountryClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\Computop\Mapper\Init\MapperInterface
     */
    public function createOrderDirectDebitMapper(): MapperInterface
    {
        return new DirectDebitMapper(
            $this->getComputopApiService(),
            $this->getRouter(),
            $this->getStore(),
            $this->getConfig(),
            $this->getRequestStack()->getCurrentRequest(),
            $this->getUtilEncodingService(),
            $this->getCountryClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\Computop\Mapper\Init\MapperInterface
     */
    public function createOrderSofortMapper(): MapperInterface
    {
        return new SofortMapper(
            $this->getComputopApiService(),
            $this->getRouter(),
            $this->getStore(),
            $this->getConfig(),
            $this->getRequestStack()->getCurrentRequest(),
            $this->getUtilEncodingService(),
            $this->getCountryClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\Computop\Mapper\Init\MapperInterface
     */
    public function createOrderPaydirektMapper(): MapperInterface
    {
        return new PaydirektMapper(
            $this->getComputopApiService(),
            $this->getRouter(),
            $this->getStore(),
            $this->getConfig(),
            $this->getRequestStack()->getCurrentRequest(),
            $this->getUtilEncodingService(),
            $this->getCountryClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\Computop\Mapper\Init\MapperInterface
     */
    public function createOrderIdealMapper(): MapperInterface
    {
        return new IdealMapper(
            $this->getComputopApiService(),
            $this->getRouter(),
            $this->getStore(),
            $this->getConfig(),
            $this->getRequestStack()->getCurrentRequest(),
            $this->getUtilEncodingService(),
            $this->getCountryClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\Computop\Mapper\Init\MapperInterface
     */
    public function createOrderEasyCreditMapper(): MapperInterface
    {
        return new EasyCreditMapper(
            $this->getComputopApiService(),
            $this->getRouter(),
            $this->getStore(),
            $this->getConfig(),
            $this->getRequestStack()->getCurrentRequest(),
            $this->getUtilEncodingService(),
            $this->getCountryClient()
        );
    }

    /**
     * @return \Spryker\Yves\Router\Router\RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::SERVICE_ROUTER);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::SERVICE_REQUEST_STACK);
    }

    /**
     * @return \SprykerEco\Yves\Computop\Dependency\Service\ComputopToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ComputopToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \SprykerEco\Yves\Computop\Dependency\Client\ComputopToCountryClientInterface
     */
    public function getCountryClient(): ComputopToCountryClientInterface
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::CLIENT_COUNTRY);
    }

    /**
     * @return \SprykerEco\Yves\Computop\Mapper\Init\PrePlace\PayPalExpressToQuoteMapperInterface
     */
    public function createPayPalExpressToQuoteMapper(): PayPalExpressToQuoteMapperInterface
    {
        return new PayPalExpressToQuoteMapper();
    }

    /**
     * @return array<PayPalExpressInitPluginInterface>
     */
    public function getPayPalExpressInitAggregatorPluginsStack(): array
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::PLUGIN_PAYPAL_EXPRESS_INIT_PLUGINS_STACK);
    }
}
