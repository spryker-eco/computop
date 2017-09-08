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
use SprykerEco\Zed\Computop\Business\Api\Adapter\RefundApiAdapter;
use SprykerEco\Zed\Computop\Business\Api\Adapter\ReverseApiAdapter;
use SprykerEco\Zed\Computop\Business\Api\Converter\AuthorizeConverter;
use SprykerEco\Zed\Computop\Business\Api\Converter\CaptureConverter;
use SprykerEco\Zed\Computop\Business\Api\Converter\InquireConverter;
use SprykerEco\Zed\Computop\Business\Api\Converter\RefundConverter;
use SprykerEco\Zed\Computop\Business\Api\Converter\ReverseConverter;
use SprykerEco\Zed\Computop\Business\Oms\Command\CancelItemManager;
use SprykerEco\Zed\Computop\Business\Order\Mapper\CreditCardMapper;
use SprykerEco\Zed\Computop\Business\Order\Mapper\PayPalMapper;
use SprykerEco\Zed\Computop\Business\Order\OrderManager;
use SprykerEco\Zed\Computop\Business\Payment\Handler\AuthorizeResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\CaptureResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\InquireResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Logger\ComputopResponseLogger;
use SprykerEco\Zed\Computop\Business\Payment\Handler\RefundResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\ReverseResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\AuthorizeCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\CaptureCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\InquireCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\RefundCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\ReverseCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\PayPal\AuthorizePayPalMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\PayPal\CapturePayPalMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\PayPal\InquirePayPalMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\PayPal\RefundPayPalMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\PayPal\ReversePayPalMapper;
use SprykerEco\Zed\Computop\Business\Payment\Request\AuthorizationRequest;
use SprykerEco\Zed\Computop\Business\Payment\Request\CaptureRequest;
use SprykerEco\Zed\Computop\Business\Payment\Request\InquireRequest;
use SprykerEco\Zed\Computop\Business\Payment\Request\RefundRequest;
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
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createOrderManagerCreditCardMapper()
    {
        return new CreditCardMapper();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createOrderManagerPayPalMapper()
    {
        return new PayPalMapper();
    }

    /**
     * @param string $paymentMethod
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Request\RequestInterface
     */
    public function createAuthorizationPaymentRequest($paymentMethod)
    {
        $paymentRequest = new AuthorizationRequest(
            $this->createAuthorizeAdapter(),
            $this->createAuthorizeConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper($this->createAuthorizeCreditCardMapper());
        $paymentRequest->registerMapper($this->createAuthorizePayPalMapper());

        return $paymentRequest;
    }

    /**
     * @param string $paymentMethod
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Request\RequestInterface
     */
    public function createInquirePaymentRequest($paymentMethod)
    {
        $paymentRequest = new InquireRequest(
            $this->createInquireAdapter(),
            $this->createInquireConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper($this->createInquireCreditCardMapper());
        $paymentRequest->registerMapper($this->createInquirePayPalMapper());

        return $paymentRequest;
    }

    /**
     * @param string $paymentMethod
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Request\RequestInterface
     */
    public function createReversePaymentRequest($paymentMethod)
    {
        $paymentRequest = new ReverseRequest(
            $this->createReverseAdapter(),
            $this->createReverseConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper($this->createReverseCreditCardMapper());
        $paymentRequest->registerMapper($this->createReversePayPalMapper());

        return $paymentRequest;
    }

    /**
     * @param string $paymentMethod
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Request\RequestInterface
     */
    public function createCapturePaymentRequest($paymentMethod)
    {
        $paymentRequest = new CaptureRequest(
            $this->createCaptureAdapter(),
            $this->createCaptureConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper($this->createCaptureCreditCardMapper());
        $paymentRequest->registerMapper($this->createCapturePayPalMapper());

        return $paymentRequest;
    }

    /**
     * @param string $paymentMethod
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Request\RequestInterface
     */
    public function createRefundPaymentRequest($paymentMethod)
    {
        $paymentRequest = new RefundRequest(
            $this->createRefundAdapter(),
            $this->createRefundConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper($this->createRefundCreditCardMapper());
        $paymentRequest->registerMapper($this->createRefundPayPalMapper());

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
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    protected function createRefundAdapter()
    {
        return new RefundApiAdapter($this->getConfig());
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
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    protected function createRefundConverter()
    {
        return new RefundConverter($this->getComputopService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    protected function createAuthorizeCreditCardMapper()
    {
        return new AuthorizeCreditCardMapper($this->getComputopService(), $this->getConfig(), $this->getQueryContainer());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    protected function createReverseCreditCardMapper()
    {
        return new ReverseCreditCardMapper($this->getComputopService(), $this->getConfig(), $this->getQueryContainer());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    protected function createInquireCreditCardMapper()
    {
        return new InquireCreditCardMapper($this->getComputopService(), $this->getConfig(), $this->getQueryContainer());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    protected function createCaptureCreditCardMapper()
    {
        return new CaptureCreditCardMapper($this->getComputopService(), $this->getConfig(), $this->getQueryContainer());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    protected function createRefundCreditCardMapper()
    {
        return new RefundCreditCardMapper($this->getComputopService(), $this->getConfig(), $this->getQueryContainer());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    protected function createAuthorizePayPalMapper()
    {
        return new AuthorizePayPalMapper($this->getComputopService(), $this->getConfig(), $this->getQueryContainer());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    protected function createReversePayPalMapper()
    {
        return new ReversePayPalMapper($this->getComputopService(), $this->getConfig(), $this->getQueryContainer());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    protected function createInquirePayPalMapper()
    {
        return new InquirePayPalMapper($this->getComputopService(), $this->getConfig(), $this->getQueryContainer());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    protected function createCapturePayPalMapper()
    {
        return new CapturePayPalMapper($this->getComputopService(), $this->getConfig(), $this->getQueryContainer());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    protected function createRefundPayPalMapper()
    {
        return new RefundPayPalMapper($this->getComputopService(), $this->getConfig(), $this->getQueryContainer());
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
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\ResponseHandlerInterface
     */
    public function createRefundResponseHandler()
    {
        return new RefundResponseHandler(
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
        return new CancelItemManager($this->getQueryContainer());
    }

}
