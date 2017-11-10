<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Zed\Computop\Business\Api\ComputopBusinessApiFactory;
use SprykerEco\Zed\Computop\Business\Hook\ComputopPostSaveHook;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitIdealMapper;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitPaydirektMapper;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitSofortMapper;
use SprykerEco\Zed\Computop\Business\Oms\Command\AuthorizeCommandHandler;
use SprykerEco\Zed\Computop\Business\Oms\Command\CancelCommandHandler;
use SprykerEco\Zed\Computop\Business\Oms\Command\CaptureCommandHandler;
use SprykerEco\Zed\Computop\Business\Oms\Command\Manager\AuthorizeManager;
use SprykerEco\Zed\Computop\Business\Oms\Command\Manager\CancelManager;
use SprykerEco\Zed\Computop\Business\Oms\Command\Manager\CaptureManager;
use SprykerEco\Zed\Computop\Business\Oms\Command\Manager\RefundManager;
use SprykerEco\Zed\Computop\Business\Oms\Command\RefundCommandHandler;
use SprykerEco\Zed\Computop\Business\Order\ComputopBusinessOrderFactory;
use SprykerEco\Zed\Computop\Business\Order\OrderManager;
use SprykerEco\Zed\Computop\Business\Payment\Handler\AuthorizeHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\CaptureHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\InquireHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Logger\ComputopResponseLogger;
use SprykerEco\Zed\Computop\Business\Payment\Handler\RefundHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\ReverseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\AuthorizeSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\CaptureSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\IdealResponseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\PaydirektResponseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\SofortResponseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\InquireSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\RefundSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\ReverseSaver;
use SprykerEco\Zed\Computop\ComputopDependencyProvider;

/**
 * @method \SprykerEco\Zed\Computop\ComputopConfig getConfig()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface getQueryContainer()
 */
class ComputopBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\ComputopBusinessApiFactoryInterface
     */
    public function createApiFactory()
    {
        return new ComputopBusinessApiFactory();
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
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface
     */
    protected function createPostSaveSofortMapper()
    {
        return new InitSofortMapper($this->getConfig(), $this->getComputopService());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface
     */
    protected function createPostSavePaydirektMapper()
    {
        return new InitPaydirektMapper($this->getConfig(), $this->getComputopService());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface
     */
    protected function createPostSaveIdealMapper()
    {
        return new InitIdealMapper($this->getConfig(), $this->getComputopService());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface
     */
    public function createAuthorizationPaymentRequest()
    {
        return $this->createApiFactory()->createAuthorizationPaymentRequest();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface
     */
    public function createInquirePaymentRequest()
    {
        return $this->createApiFactory()->createInquirePaymentRequest();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface
     */
    public function createReversePaymentRequest()
    {
        return $this->createApiFactory()->createReversePaymentRequest();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface
     */
    public function createCapturePaymentRequest()
    {
        return $this->createApiFactory()->createCapturePaymentRequest();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\RequestInterface
     */
    public function createRefundPaymentRequest()
    {
        return $this->createApiFactory()->createRefundPaymentRequest();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\HandlerInterface
     */
    public function createAuthorizeHandler()
    {
        return new AuthorizeHandler(
            $this->createAuthorizationPaymentRequest(),
            $this->createAuthorizeSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\HandlerInterface
     */
    public function createReverseHandler()
    {
        return new ReverseHandler(
            $this->createReversePaymentRequest(),
            $this->createReverseSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\HandlerInterface
     */
    public function createInquireHandler()
    {
        return new InquireHandler(
            $this->createInquirePaymentRequest(),
            $this->createInquireSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\HandlerInterface
     */
    public function createCaptureHandler()
    {
        return new CaptureHandler(
            $this->createCapturePaymentRequest(),
            $this->createCaptureSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\HandlerInterface
     */
    public function createRefundHandler()
    {
        return new RefundHandler(
            $this->createRefundPaymentRequest(),
            $this->createRefundSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\SaverInterface
     */
    public function createAuthorizeSaver()
    {
        return new AuthorizeSaver(
            $this->getQueryContainer(),
            $this->createComputopResponseLogger(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\SaverInterface
     */
    public function createReverseSaver()
    {
        return new ReverseSaver(
            $this->getQueryContainer(),
            $this->createComputopResponseLogger(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\SaverInterface
     */
    public function createInquireSaver()
    {
        return new InquireSaver(
            $this->getQueryContainer(),
            $this->createComputopResponseLogger(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\SaverInterface
     */
    public function createCaptureSaver()
    {
        return new CaptureSaver(
            $this->getQueryContainer(),
            $this->createComputopResponseLogger(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\SaverInterface
     */
    public function createRefundSaver()
    {
        return new RefundSaver(
            $this->getQueryContainer(),
            $this->createComputopResponseLogger(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\InitResponseSaverInterface
     */
    public function createSofortResponseSaver()
    {
        return new SofortResponseSaver($this->getQueryContainer(), $this->getOmsFacade(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\InitResponseSaverInterface
     */
    public function createIdealResponseSaver()
    {
        return new IdealResponseSaver($this->getQueryContainer(), $this->getOmsFacade(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\InitResponseSaverInterface
     */
    public function createPaydirektResponseSaver()
    {
        return new PaydirektResponseSaver($this->getQueryContainer(), $this->getOmsFacade(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Logger\ComputopResponseLoggerInterface
     */
    public function createComputopResponseLogger()
    {
        return new ComputopResponseLogger();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Dependency\ComputopToStoreInterface
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::STORE);
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Oms\Command\CommandHandlerInterface
     */
    public function createAuthorizeCommandHandler()
    {
        return new AuthorizeCommandHandler(
            $this->createAuthorizeHandler(),
            $this->createAuthorizeManager()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Oms\Command\CommandHandlerInterface
     */
    public function createCancelCommandHandler()
    {
        return new CancelCommandHandler(
            $this->createInquireHandler(),
            $this->createReverseHandler(),
            $this->createCancelManager(),
            $this->getFlashMessengerFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Oms\Command\CommandHandlerInterface
     */
    public function createCaptureCommandHandler()
    {
        return new CaptureCommandHandler(
            $this->createCaptureHandler(),
            $this->createCaptureManager()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Oms\Command\CommandHandlerInterface
     */
    public function createRefundCommandHandler()
    {
        return new RefundCommandHandler(
            $this->createRefundHandler(),
            $this->createRefundManager()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Oms\Command\Manager\ManagerInterface
     */
    protected function createAuthorizeManager()
    {
        return new AuthorizeManager($this->getQueryContainer(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Oms\Command\Manager\CancelManagerInterface
     */
    protected function createCancelManager()
    {
        return new CancelManager($this->getQueryContainer(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Oms\Command\Manager\ManagerInterface
     */
    protected function createCaptureManager()
    {
        return new CaptureManager($this->getQueryContainer(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Oms\Command\Manager\ManagerInterface
     */
    protected function createRefundManager()
    {
        return new RefundManager($this->getQueryContainer(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\ComputopBusinessOrderFactoryInterface
     */
    protected function createOrderFactory()
    {
        return new ComputopBusinessOrderFactory();
    }

    /**
     * @return \SprykerEco\Service\Computop\ComputopServiceInterface
     */
    protected function getComputopService()
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::SERVICE_COMPUTOP);
    }

    /**
     * @return \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsFacadeInterface
     */
    protected function getOmsFacade()
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMessengerFacadeInterface
     */
    protected function getFlashMessengerFacade()
    {
        return $this->getProvidedDependency(
            ComputopDependencyProvider::FACADE_FLASH_MESSENGER
        );
    }
}
