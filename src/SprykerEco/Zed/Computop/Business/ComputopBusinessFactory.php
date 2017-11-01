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
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Order\IdealMapper;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Order\PaydirektMapper;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Order\SofortMapper;
use SprykerEco\Zed\Computop\Business\Oms\Command\CancelItemManager;
use SprykerEco\Zed\Computop\Business\Order\ComputopBusinessOrderFactory;
use SprykerEco\Zed\Computop\Business\Order\OrderManager;
use SprykerEco\Zed\Computop\Business\Payment\Handler\AuthorizeResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\CaptureResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\InquireResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Logger\ComputopResponseLogger;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Order\IdealResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Order\PaydirektResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Order\SofortResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\RefundResponseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\ReverseResponseHandler;
use SprykerEco\Zed\Computop\ComputopDependencyProvider;

/**
 * @method \SprykerEco\Zed\Computop\ComputopConfig getConfig()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface getQueryContainer()
 */
class ComputopBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @var null|\SprykerEco\Zed\Computop\Business\Api\ComputopBusinessApiFactory
     */
    protected $apiFactory = null;

    /**
     * @var null|\SprykerEco\Zed\Computop\Business\Order\ComputopBusinessOrderFactory
     */
    protected $orderFactory = null;

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\ComputopBusinessApiFactoryInterface
     */
    public function createApiFactory()
    {
        if ($this->apiFactory === null) {
            $this->apiFactory = new ComputopBusinessApiFactory();
        }

        return $this->apiFactory;
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\ComputopBusinessOrderFactoryInterface
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
        $orderSaver->registerMapper($this->createOrderFactory()->createOrderPaydirektMapper());
        $orderSaver->registerMapper($this->createOrderFactory()->createOrderIdealMapper());

        return $orderSaver;
    }

    /**
     * @param string $paymentMethod
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface
     */
    public function createAuthorizationPaymentRequest($paymentMethod, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return $this->createApiFactory()->createAuthorizationPaymentRequest($paymentMethod, $computopHeaderPayment);
    }

    /**
     * @param string $paymentMethod
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface
     */
    public function createInquirePaymentRequest($paymentMethod, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return $this->createApiFactory()->createInquirePaymentRequest($paymentMethod, $computopHeaderPayment);
    }

    /**
     * @param string $paymentMethod
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface
     */
    public function createReversePaymentRequest($paymentMethod, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return $this->createApiFactory()->createReversePaymentRequest($paymentMethod, $computopHeaderPayment);
    }

    /**
     * @param string $paymentMethod
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface
     */
    public function createCapturePaymentRequest($paymentMethod, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return $this->createApiFactory()->createCapturePaymentRequest($paymentMethod, $computopHeaderPayment);
    }

    /**
     * @param string $paymentMethod
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface
     */
    public function createRefundPaymentRequest($paymentMethod, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return $this->createApiFactory()->createRefundPaymentRequest($paymentMethod, $computopHeaderPayment);
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
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Order\OrderResponseHandlerInterface
     */
    public function createSofortResponseHandler()
    {
        return new SofortResponseHandler($this->getQueryContainer(), $this->getOmsFacade(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Order\OrderResponseHandlerInterface
     */
    public function createIdealResponseHandler()
    {
        return new IdealResponseHandler($this->getQueryContainer(), $this->getOmsFacade(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Order\OrderResponseHandlerInterface
     */
    public function createPaydirektResponseHandler()
    {
        return new PaydirektResponseHandler($this->getQueryContainer(), $this->getOmsFacade(), $this->getConfig());
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
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getPaymentMethod(OrderTransfer $orderTransfer)
    {
        $paymentsArray = $orderTransfer->getPayments()->getArrayCopy();

        return array_shift($paymentsArray)->getPaymentMethod();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\ComputopPostSaveHookInterface
     */
    public function createPostSaveHook()
    {
        $postSaveHook = new ComputopPostSaveHook($this->getConfig());
        $postSaveHook->registerMapper($this->createPostSaveSofortMapper());
        $postSaveHook->registerMapper($this->createPostSavePaydirektMapper());
        $postSaveHook->registerMapper($this->createPostSaveIdealMapper());

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
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Order\AbstractMapperInterface
     */
    protected function createPostSavePaydirektMapper()
    {
        return new PaydirektMapper($this->getConfig(), $this->getComputopService());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Order\AbstractMapperInterface
     */
    protected function createPostSaveIdealMapper()
    {
        return new IdealMapper($this->getConfig(), $this->getComputopService());
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
}
