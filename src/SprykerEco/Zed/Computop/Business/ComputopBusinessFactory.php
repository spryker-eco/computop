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
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Order\SofortMapper;
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
    public function createMapperFactory()
    {
        if ($this->mapperFactory === null) {
            $this->mapperFactory = new ComputopBusinessMapperFactory();
        }

        return $this->mapperFactory;
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\ComputopBusinessApiFactory
     */
    public function createApiFactory()
    {
        if ($this->apiFactory === null) {
            $this->apiFactory = new ComputopBusinessApiFactory();
        }

        return $this->apiFactory;
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\ComputopBusinessOrderFactory
     */
    public function createOrderFactory()
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

        $orderSaver->registerMapper($this->createOrderFactory()->createOrderCreditCardMapper());
        $orderSaver->registerMapper($this->createOrderFactory()->createOrderPayPalMapper());
        $orderSaver->registerMapper($this->createOrderFactory()->createOrderDirectDebitMapper());
        $orderSaver->registerMapper($this->createOrderFactory()->createOrderSofortMapper());

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
            $this->getAuthorizeAdapter(),
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
     * @return \SprykerEco\Zed\Computop\Business\Payment\Request\RequestInterface
     */
    public function createInquirePaymentRequest($paymentMethod, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        $paymentRequest = new InquireRequest(
            $this->getInquireAdapter(),
            $this->createApiFactory()->createInquireConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper($this->createMapperFactory()->createInquireCreditCardMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createInquirePayPalMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createInquireDirectDebitMapper($computopHeaderPayment));

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
            $this->getReverseAdapter(),
            $this->createApiFactory()->createReverseConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper($this->createMapperFactory()->createReverseCreditCardMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createReversePayPalMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createReverseDirectDebitMapper($computopHeaderPayment));

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
            $this->getCaptureAdapter(),
            $this->createApiFactory()->createCaptureConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper($this->createMapperFactory()->createCaptureCreditCardMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createCapturePayPalMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createCaptureDirectDebitMapper($computopHeaderPayment));

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
            $this->getRefundAdapter(),
            $this->createApiFactory()->createRefundConverter(),
            $paymentMethod
        );

        $paymentRequest->registerMapper($this->createMapperFactory()->createRefundCreditCardMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createRefundPayPalMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createRefundDirectDebitMapper($computopHeaderPayment));
        $paymentRequest->registerMapper($this->createMapperFactory()->createRefundSofortMapper($computopHeaderPayment));

        return $paymentRequest;
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\ResponseHandlerInterface
     */
    public function createAuthorizeResponseHandler()
    {
        return new AuthorizeResponseHandler(
            $this->getQueryContainer(),
            $this->createComputopResponseLogger(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\ResponseHandlerInterface
     */
    public function createReverseResponseHandler()
    {
        return new ReverseResponseHandler(
            $this->getQueryContainer(),
            $this->createComputopResponseLogger(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\ResponseHandlerInterface
     */
    public function createInquireResponseHandler()
    {
        return new InquireResponseHandler(
            $this->getQueryContainer(),
            $this->createComputopResponseLogger(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\ResponseHandlerInterface
     */
    public function createCaptureResponseHandler()
    {
        return new CaptureResponseHandler(
            $this->getQueryContainer(),
            $this->createComputopResponseLogger(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\ResponseHandlerInterface
     */
    public function createRefundResponseHandler()
    {
        return new RefundResponseHandler(
            $this->getQueryContainer(),
            $this->createComputopResponseLogger(),
            $this->getConfig()
        );
    }

    /**
     * TODO: Check
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Order\SofortResponseHandler
     */
    public function createSofortResponseHandler()
    {
        return new SofortResponseHandler($this->getQueryContainer(), $this->getOmsFacade(), $this->getConfig());
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
        return new CancelItemManager($this->getQueryContainer(), $this->getConfig());
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
        $postSaveHook = new ComputopPostSaveHook($this->getConfig());
        $postSaveHook->registerMapper($this->createPostSaveSofortMapper());

        return $postSaveHook;
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Order\AbstractMapperInterface
     */
    protected function createPostSaveSofortMapper()
    {
        return new SofortMapper($this->getConfig(), $this->getComputopService());
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
    protected function getAuthorizeAdapter()
    {
        return $this->createApiFactory()->createAuthorizeAdapter();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    protected function getInquireAdapter()
    {
        return $this->createApiFactory()->createInquireAdapter();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    protected function getReverseAdapter()
    {
        return $this->createApiFactory()->createReverseAdapter();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    protected function getCaptureAdapter()
    {
        return $this->createApiFactory()->createCaptureAdapter();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    protected function getRefundAdapter()
    {
        return $this->createApiFactory()->createRefundAdapter();
    }

}
