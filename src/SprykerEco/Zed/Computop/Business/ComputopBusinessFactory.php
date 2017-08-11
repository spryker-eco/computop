<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Zed\Computop\Business\Api\Adapter\AuthorizeApiAdapter;
use SprykerEco\Zed\Computop\Business\Api\Adapter\CaptureApiAdapter;
use SprykerEco\Zed\Computop\Business\Api\Adapter\InquireApiAdapter;
use SprykerEco\Zed\Computop\Business\Api\Adapter\ReverseApiAdapter;
use SprykerEco\Zed\Computop\Business\Api\Converter\AuthorizeConverter;
use SprykerEco\Zed\Computop\Business\Api\Converter\CaptureConverter;
use SprykerEco\Zed\Computop\Business\Api\Converter\InquireConverter;
use SprykerEco\Zed\Computop\Business\Api\Converter\ReverseConverter;
use SprykerEco\Zed\Computop\Business\Oms\Command\CancelItemManager;
use SprykerEco\Zed\Computop\Business\Order\OrderManager;
use SprykerEco\Zed\Computop\Business\Payment\Handler\AuthorizeResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\CaptureResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\InquireResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Logger\ComputopResponseLogger;
use SprykerEco\Zed\Computop\Business\Payment\Handler\ReverseResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\AuthorizeCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\CaptureCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\InquireCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\ReverseCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Payment\Request\AuthorizationRequest;
use SprykerEco\Zed\Computop\Business\Payment\Request\CaptureRequest;
use SprykerEco\Zed\Computop\Business\Payment\Request\InquireRequest;
use SprykerEco\Zed\Computop\Business\Payment\Request\ReverseRequest;
use SprykerEco\Zed\Computop\ComputopDependencyProvider;

/**
 * @method \SprykerEco\Zed\Computop\ComputopConfig getConfig()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainer getQueryContainer()
 */
class ComputopBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\OrderManagerInterface
     */
    public function createOrderSaver()
    {
        return new OrderManager();
    }

    /**
     * @param string $paymentMethod
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Request\CreditCartRequestInterface
     */
    public function createAuthorizationPaymentRequest($paymentMethod)
    {
        $paymentRequest = new AuthorizationRequest(
            $this->createAuthorizeAdapter(),
            $this->createAuthorizeConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper(
            $this->createAuthorizeCreditCardMapper()
        );

        return $paymentRequest;
    }

    /**
     * @param string $paymentMethod
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Request\CreditCartRequestInterface
     */
    public function createInquirePaymentRequest($paymentMethod)
    {
        $paymentRequest = new InquireRequest(
            $this->createInquireAdapter(),
            $this->createInquireConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper(
            $this->createInquireCreditCardMapper()
        );

        return $paymentRequest;
    }

    /**
     * @param string $paymentMethod
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Request\CreditCartRequestInterface
     */
    public function createReversePaymentRequest($paymentMethod)
    {
        $paymentRequest = new ReverseRequest(
            $this->createReverseAdapter(),
            $this->createReverseConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper(
            $this->createReverseCreditCardMapper()
        );

        return $paymentRequest;
    }

    /**
     * @param string $paymentMethod
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Request\CreditCartRequestInterface
     */
    public function createCapturePaymentRequest($paymentMethod)
    {
        $paymentRequest = new CaptureRequest(
            $this->createCaptureAdapter(),
            $this->createCaptureConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper(
            $this->createCaptureCreditCardMapper()
        );

        return $paymentRequest;
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    protected function createAuthorizeAdapter()
    {
        return new AuthorizeApiAdapter($this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    protected function createReverseAdapter()
    {
        return new ReverseApiAdapter($this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    protected function createInquireAdapter()
    {
        return new InquireApiAdapter($this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    protected function createCaptureAdapter()
    {
        return new CaptureApiAdapter($this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    protected function createAuthorizeConverter()
    {
        return new AuthorizeConverter($this->getComputopService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    protected function createReverseConverter()
    {
        return new ReverseConverter($this->getComputopService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    protected function createInquireConverter()
    {
        return new InquireConverter($this->getComputopService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    protected function createCaptureConverter()
    {
        return new CaptureConverter($this->getComputopService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\CreditCardMapperInterface
     */
    protected function createAuthorizeCreditCardMapper()
    {
        return new AuthorizeCreditCardMapper($this->getComputopService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\CreditCardMapperInterface
     */
    protected function createReverseCreditCardMapper()
    {
        return new ReverseCreditCardMapper($this->getComputopService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\CreditCardMapperInterface
     */
    protected function createInquireCreditCardMapper()
    {
        return new InquireCreditCardMapper($this->getComputopService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\CreditCardMapperInterface
     */
    protected function createCaptureCreditCardMapper()
    {
        return new CaptureCreditCardMapper($this->getComputopService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceInterface
     */
    protected function getComputopService()
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::COMPUTOP_SERVICE);
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\ResponseHandlerInterface
     */
    public function createAuthorizeResponseHandler()
    {
        return new AuthorizeResponseHandler(
            $this->getQueryContainer(),
            $this->createComputopResponseLogger()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\ResponseHandlerInterface
     */
    public function createReverseResponseHandler()
    {
        return new ReverseResponseHandler(
            $this->getQueryContainer(),
            $this->createComputopResponseLogger()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\ResponseHandlerInterface
     */
    public function createInquireResponseHandler()
    {
        return new InquireResponseHandler(
            $this->getQueryContainer(),
            $this->createComputopResponseLogger()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\ResponseHandlerInterface
     */
    public function createCaptureResponseHandler()
    {
        return new CaptureResponseHandler(
            $this->getQueryContainer(),
            $this->createComputopResponseLogger()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Logger\ComputopResponseLoggerInterface
     */
    public function createComputopResponseLogger()
    {
        return new ComputopResponseLogger();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Oms\Command\CancelItemManagerInterface
     */
    public function createCancelItemManager()
    {
        return new CancelItemManager();
    }

}
