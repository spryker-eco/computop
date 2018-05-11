<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api;

use SprykerEco\Zed\Computop\Business\Api\Adapter\AuthorizeApiAdapter;
use SprykerEco\Zed\Computop\Business\Api\Adapter\CaptureApiAdapter;
use SprykerEco\Zed\Computop\Business\Api\Adapter\EasyCredit\EasyCreditAuthorizeApiAdapter;
use SprykerEco\Zed\Computop\Business\Api\Adapter\EasyCredit\EasyCreditStatusApiAdapter;
use SprykerEco\Zed\Computop\Business\Api\Adapter\InquireApiAdapter;
use SprykerEco\Zed\Computop\Business\Api\Adapter\RefundApiAdapter;
use SprykerEco\Zed\Computop\Business\Api\Adapter\ReverseApiAdapter;
use SprykerEco\Zed\Computop\Business\Api\Converter\AuthorizeConverter;
use SprykerEco\Zed\Computop\Business\Api\Converter\CaptureConverter;
use SprykerEco\Zed\Computop\Business\Api\Converter\EasyCredit\PayNowInitConverter;
use SprykerEco\Zed\Computop\Business\Api\Converter\InquireConverter;
use SprykerEco\Zed\Computop\Business\Api\Converter\RefundConverter;
use SprykerEco\Zed\Computop\Business\Api\Converter\ReverseConverter;
use SprykerEco\Zed\Computop\Business\Api\Mapper\ComputopBusinessMapperFactory;
use SprykerEco\Zed\Computop\Business\Api\Request\PostPlace\AuthorizationRequest;
use SprykerEco\Zed\Computop\Business\Api\Request\PostPlace\CaptureRequest;
use SprykerEco\Zed\Computop\Business\Api\Request\PostPlace\InquireRequest;
use SprykerEco\Zed\Computop\Business\Api\Request\PostPlace\RefundRequest;
use SprykerEco\Zed\Computop\Business\Api\Request\PostPlace\ReverseRequest;
use SprykerEco\Zed\Computop\Business\Api\Request\PrePlace\EasyCreditStatusRequest;
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
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\PostPlace\PostPlaceRequestInterface
     */
    public function createAuthorizationPaymentRequest()
    {
        $paymentRequest = new AuthorizationRequest(
            $this->createAuthorizeAdapter(),
            $this->createApiFactory()->createAuthorizeConverter()
        );

        $paymentRequest->registerMapper($this->createMapperFactory()->createAuthorizeCreditCardMapper());
        $paymentRequest->registerMapper($this->createMapperFactory()->createAuthorizeEasyCreditMapper());
        $paymentRequest->registerMapper($this->createMapperFactory()->createAuthorizePayPalMapper());

        return $paymentRequest;
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\PostPlace\PostPlaceRequestInterface
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
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\PostPlace\PostPlaceRequestInterface
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
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\PostPlace\PostPlaceRequestInterface
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
        $paymentRequest->registerMapper($this->createMapperFactory()->createCaptureIdealMapper());

        return $paymentRequest;
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\PostPlace\PostPlaceRequestInterface
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
        $paymentRequest->registerMapper($this->createMapperFactory()->createRefundIdealMapper());

        return $paymentRequest;
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\PrePlace\PrePlaceRequestInterface
     */
    public function createEasyCreditStatusRequest()
    {
        $paymentRequest = new EasyCreditStatusRequest(
            $this->createEasyCreditStatusAdapter(),
            $this->createApiFactory()->createEasyCreditConverter(),
            $this->createMapperFactory()->createStatusEasyCreditMapper()
        );

        return $paymentRequest;
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\PostPlace\PostPlaceRequestInterface
     */
    public function createEasyCreditAuthorizeRequest()
    {
        $paymentRequest = new AuthorizationRequest(
            $this->createEasyCreditAuthorizeAdapter(),
            $this->createApiFactory()->createAuthorizeConverter()
        );

        $paymentRequest->registerMapper($this->createMapperFactory()->createAuthorizeCreditCardMapper());
        $paymentRequest->registerMapper($this->createMapperFactory()->createAuthorizeEasyCreditMapper());
        $paymentRequest->registerMapper($this->createMapperFactory()->createAuthorizePayPalMapper());

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
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    public function createEasyCreditConverter()
    {
        return new PayNowInitConverter($this->getComputopService(), $this->getConfig());
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
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    protected function createEasyCreditStatusAdapter()
    {
        return new EasyCreditStatusApiAdapter($this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    protected function createEasyCreditAuthorizeAdapter()
    {
        return new EasyCreditAuthorizeApiAdapter($this->getConfig());
    }
}
