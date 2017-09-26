<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business;

use Generated\Shared\Transfer\ComputopHeaderPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Zed\Computop\Business\Api\ComputopBusinessApiFactory;
use SprykerEco\Zed\Computop\Business\Hook\ComputopPostSaveHook;
use SprykerEco\Zed\Computop\Business\Oms\Command\CancelItemManager;
use SprykerEco\Zed\Computop\Business\Order\ComputopBusinessOrderFactory;
use SprykerEco\Zed\Computop\Business\Order\OrderManager;
use SprykerEco\Zed\Computop\Business\Payment\Handler\AuthorizeResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\CaptureResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\InquireResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Logger\ComputopResponseLogger;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Order\SofortResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\RefundResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\ReverseResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\ComputopBusinessMapperFactory;
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
     * @var null|\SprykerEco\Zed\Computop\Business\Payment\Mapper\ComputopBusinessMapperFactory
     */
    protected $mapperFactory = null;

    /**
     * @var null|\SprykerEco\Zed\Computop\Business\Api\ComputopBusinessApiFactory
     */
    protected $apiFactory = null;

    /**
     * @var null|\SprykerEco\Zed\Computop\Business\Order\ComputopBusinessOrderFactory
     */
    protected $orderFactory = null;

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\ComputopBusinessMapperFactory
     */
    public function getMapperFactory()
    {
        if ($this->mapperFactory === null) {
            $this->mapperFactory = new ComputopBusinessMapperFactory();
        }

        return $this->mapperFactory;
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\ComputopBusinessApiFactory
     */
    public function getApiFactory()
    {
        if ($this->apiFactory === null) {
            $this->apiFactory = new ComputopBusinessApiFactory();
        }

        return $this->apiFactory;
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\ComputopBusinessOrderFactory
     */
    public function getOrderFactory()
    {
        if ($this->orderFactory === null) {
            $this->orderFactory = new ComputopBusinessOrderFactory();
        }

        return $this->orderFactory;
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\OrderManagerInterface
     */
    public function createOrderSaver()
    {
        $orderSaver = new OrderManager($this->getConfig());

        $orderSaver->registerMapper($this->getOrderFactory()->createOrderCreditCardMapper());
        $orderSaver->registerMapper($this->getOrderFactory()->createOrderPayPalMapper());
        $orderSaver->registerMapper($this->getOrderFactory()->createOrderDirectDebitMapper());
        $orderSaver->registerMapper($this->getOrderFactory()->createOrderSofortMapper());

        return $orderSaver;
    }

    /**
     * @param string $paymentMethod
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Request\RequestInterface
     */
    public function createAuthorizationPaymentRequest($paymentMethod, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        $paymentRequest = new AuthorizationRequest(
            $this->createAuthorizeAdapter(),
            $this->getApiFactory()->createAuthorizeConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper($this->getMapperFactory()->createAuthorizeCreditCardMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->getMapperFactory()->createAuthorizePayPalMapper($computopHeaderPayment));

        return $paymentRequest;
    }

    /**
     * @param string $paymentMethod
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Request\RequestInterface
     */
    public function createInquirePaymentRequest($paymentMethod, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        $paymentRequest = new InquireRequest(
            $this->createInquireAdapter(),
            $this->getApiFactory()->createInquireConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper($this->getMapperFactory()->createInquireCreditCardMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->getMapperFactory()->createInquirePayPalMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->getMapperFactory()->createInquireDirectDebitMapper($computopHeaderPayment));

        return $paymentRequest;
    }

    /**
     * @param string $paymentMethod
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Request\RequestInterface
     */
    public function createReversePaymentRequest($paymentMethod, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        $paymentRequest = new ReverseRequest(
            $this->createReverseAdapter(),
            $this->getApiFactory()->createReverseConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper($this->getMapperFactory()->createReverseCreditCardMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->getMapperFactory()->createReversePayPalMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->getMapperFactory()->createReverseDirectDebitMapper($computopHeaderPayment));

        return $paymentRequest;
    }

    /**
     * @param string $paymentMethod
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Request\RequestInterface
     */
    public function createCapturePaymentRequest($paymentMethod, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        $paymentRequest = new CaptureRequest(
            $this->createCaptureAdapter(),
            $this->getApiFactory()->createCaptureConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper($this->getMapperFactory()->createCaptureCreditCardMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->getMapperFactory()->createCapturePayPalMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->getMapperFactory()->createCaptureDirectDebitMapper($computopHeaderPayment));

        return $paymentRequest;
    }

    /**
     * @param string $paymentMethod
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Request\RequestInterface
     */
    public function createRefundPaymentRequest($paymentMethod, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        $paymentRequest = new RefundRequest(
            $this->createRefundAdapter(),
            $this->getApiFactory()->createRefundConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper($this->getMapperFactory()->createRefundCreditCardMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->getMapperFactory()->createRefundPayPalMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->getMapperFactory()->createRefundDirectDebitMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->getMapperFactory()->createRefundSofortMapper($computopHeaderPayment));

        return $paymentRequest;
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
     * TODO: Check
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Order\SofortResponseHandler
     */
    public function createSofortResponseHandler()
    {
        return new SofortResponseHandler($this->getQueryContainer(), $this->getOmsFacade());
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

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return mixed
     */
    public function getPaymentMethod(OrderTransfer $orderTransfer)
    {
        $paymentsArray = $orderTransfer->getPayments()->getArrayCopy();

        return array_shift($paymentsArray)->getPaymentMethod();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\ComputopPostSaveHook
     */
    public function createPostSaveHook()
    {
        return new ComputopPostSaveHook($this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceInterface
     */
    protected function getComputopService()
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::COMPUTOP_SERVICE);
    }

    /**
     * @return \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsInterface
     */
    protected function getOmsFacade()
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    protected function createAuthorizeAdapter()
    {
        return $this->getApiFactory()->createAuthorizeAdapter();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    protected function createInquireAdapter()
    {
        return $this->getApiFactory()->createInquireAdapter();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    protected function createReverseAdapter()
    {
        return $this->getApiFactory()->createReverseAdapter();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    protected function createCaptureAdapter()
    {
        return $this->getApiFactory()->createCaptureAdapter();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    protected function createRefundAdapter()
    {
        return $this->getApiFactory()->createRefundAdapter();
    }

}
