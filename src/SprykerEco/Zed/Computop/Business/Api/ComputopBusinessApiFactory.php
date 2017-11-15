<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api;

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
use SprykerEco\Zed\Computop\Business\Api\Mapper\ComputopBusinessMapperFactory;
use SprykerEco\Zed\Computop\Business\Api\Request\AuthorizationRequest;
use SprykerEco\Zed\Computop\Business\Api\Request\CaptureRequest;
use SprykerEco\Zed\Computop\Business\Api\Request\InquireRequest;
use SprykerEco\Zed\Computop\Business\Api\Request\RefundRequest;
use SprykerEco\Zed\Computop\Business\Api\Request\ReverseRequest;
use SprykerEco\Zed\Computop\Business\ComputopBusinessFactory;

/**
 * @method \SprykerEco\Zed\Computop\ComputopConfig getConfig()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface getQueryContainer()
 */
class ComputopBusinessApiFactory extends ComputopBusinessFactory implements ComputopBusinessApiFactoryInterface
{
    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ComputopBusinessMapperFactoryInterface
     */
    protected function createMapperFactory()
    {
        return new ComputopBusinessMapperFactory();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface
     */
    public function createAuthorizationPaymentRequest()
    {
        $paymentRequest = new AuthorizationRequest(
            $this->createAuthorizeAdapter(),
            $this->createApiFactory()->createAuthorizeConverter()
        );

        $paymentRequest->registerMapper($this->createMapperFactory()->createAuthorizeCreditCardMapper());
        $paymentRequest->registerMapper($this->createMapperFactory()->createAuthorizePayPalMapper());

        return $paymentRequest;
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface
     */
    public function createInquirePaymentRequest()
    {
        $paymentRequest = new InquireRequest(
            $this->createInquireAdapter(),
            $this->createApiFactory()->createInquireConverter()
        );

        $paymentRequest->registerMapper($this->createMapperFactory()->createInquireCreditCardMapper());
        $paymentRequest->registerMapper($this->createMapperFactory()->createInquirePayPalMapper());
        $paymentRequest->registerMapper($this->createMapperFactory()->createInquireDirectDebitMapper());
        $paymentRequest->registerMapper($this->createMapperFactory()->createInquirePaydirektMapper());

        return $paymentRequest;
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface
     */
    public function createReversePaymentRequest()
    {
        $paymentRequest = new ReverseRequest(
            $this->createReverseAdapter(),
            $this->createApiFactory()->createReverseConverter()
        );

        $paymentRequest->registerMapper($this->createMapperFactory()->createReverseCreditCardMapper());
        $paymentRequest->registerMapper($this->createMapperFactory()->createReversePayPalMapper());
        $paymentRequest->registerMapper($this->createMapperFactory()->createReverseDirectDebitMapper());
        $paymentRequest->registerMapper($this->createMapperFactory()->createReversePaydirektMapper());

        return $paymentRequest;
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface
     */
    public function createCapturePaymentRequest()
    {
        $paymentRequest = new CaptureRequest(
            $this->createCaptureAdapter(),
            $this->createApiFactory()->createCaptureConverter()
        );

        $paymentRequest->registerMapper($this->createMapperFactory()->createCaptureCreditCardMapper());
        $paymentRequest->registerMapper($this->createMapperFactory()->createCapturePayPalMapper());
        $paymentRequest->registerMapper($this->createMapperFactory()->createCaptureDirectDebitMapper());
        $paymentRequest->registerMapper($this->createMapperFactory()->createCapturePaydirektMapper());
        $paymentRequest->registerMapper($this->createMapperFactory()->createCaptureEasyCreditMapper());

        return $paymentRequest;
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface
     */
    public function createRefundPaymentRequest()
    {
        $paymentRequest = new RefundRequest(
            $this->createRefundAdapter(),
            $this->createApiFactory()->createRefundConverter()
        );

        $paymentRequest->registerMapper($this->createMapperFactory()->createRefundCreditCardMapper());
        $paymentRequest->registerMapper($this->createMapperFactory()->createRefundPayPalMapper());
        $paymentRequest->registerMapper($this->createMapperFactory()->createRefundDirectDebitMapper());
        $paymentRequest->registerMapper($this->createMapperFactory()->createRefundSofortMapper());
        $paymentRequest->registerMapper($this->createMapperFactory()->createRefundPaydirektMapper());
        $paymentRequest->registerMapper($this->createMapperFactory()->createRefundEasyCreditMapper());

        return $paymentRequest;
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    public function createAuthorizeConverter()
    {
        return new AuthorizeConverter($this->getComputopService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    public function createReverseConverter()
    {
        return new ReverseConverter($this->getComputopService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    public function createInquireConverter()
    {
        return new InquireConverter($this->getComputopService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    public function createCaptureConverter()
    {
        return new CaptureConverter($this->getComputopService(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    public function createRefundConverter()
    {
        return new RefundConverter($this->getComputopService(), $this->getConfig());
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
}
