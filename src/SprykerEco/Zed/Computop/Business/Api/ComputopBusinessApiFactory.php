<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api;

use Generated\Shared\Transfer\ComputopHeaderPaymentTransfer;
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
     * @var null|\SprykerEco\Zed\Computop\Business\Api\Mapper\ComputopBusinessMapperFactory
     */
    protected $mapperFactory = null;

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ComputopBusinessMapperFactoryInterface
     */
    protected function createMapperFactory()
    {
        if ($this->mapperFactory === null) {
            $this->mapperFactory = new ComputopBusinessMapperFactory();
        }

        return $this->mapperFactory;
    }

    /**
     * @param string $paymentMethod
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface
     */
    public function createAuthorizationPaymentRequest($paymentMethod, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        $paymentRequest = new AuthorizationRequest(
            $this->createAuthorizeAdapter(),
            $this->createApiFactory()->createAuthorizeConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper($this->createMapperFactory()->createAuthorizeCreditCardMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createAuthorizePayPalMapper($computopHeaderPayment));

        return $paymentRequest;
    }

    /**
     * @param string $paymentMethod
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface
     */
    public function createInquirePaymentRequest($paymentMethod, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        $paymentRequest = new InquireRequest(
            $this->createInquireAdapter(),
            $this->createApiFactory()->createInquireConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper($this->createMapperFactory()->createInquireCreditCardMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createInquirePayPalMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createInquireDirectDebitMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createInquirePaydirektMapper($computopHeaderPayment));

        return $paymentRequest;
    }

    /**
     * @param string $paymentMethod
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface
     */
    public function createReversePaymentRequest($paymentMethod, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        $paymentRequest = new ReverseRequest(
            $this->createReverseAdapter(),
            $this->createApiFactory()->createReverseConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper($this->createMapperFactory()->createReverseCreditCardMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createReversePayPalMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createReverseDirectDebitMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createReversePaydirektMapper($computopHeaderPayment));

        return $paymentRequest;
    }

    /**
     * @param string $paymentMethod
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface
     */
    public function createCapturePaymentRequest($paymentMethod, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        $paymentRequest = new CaptureRequest(
            $this->createCaptureAdapter(),
            $this->createApiFactory()->createCaptureConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper($this->createMapperFactory()->createCaptureCreditCardMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createCapturePayPalMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createCaptureDirectDebitMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createCapturePaydirektMapper($computopHeaderPayment));

        return $paymentRequest;
    }

    /**
     * @param string $paymentMethod
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface
     */
    public function createRefundPaymentRequest($paymentMethod, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        $paymentRequest = new RefundRequest(
            $this->createRefundAdapter(),
            $this->createApiFactory()->createRefundConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper($this->createMapperFactory()->createRefundCreditCardMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createRefundPayPalMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createRefundDirectDebitMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createRefundSofortMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createRefundPaydirektMapper($computopHeaderPayment));

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
