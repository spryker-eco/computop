<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Service\ComputopApi\ComputopApiServiceInterface;
use SprykerEco\Zed\Computop\Business\DefaultShippingMethodQuoteExpander\QuoteDefaultShippingMethodExpander;
use SprykerEco\Zed\Computop\Business\DefaultShippingMethodQuoteExpander\QuoteDefaultShippingMethodExpanderInterface;
use SprykerEco\Zed\Computop\Business\Hook\ComputopPostSaveHook;
use SprykerEco\Zed\Computop\Business\Hook\ComputopPostSaveHookInterface;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitDirectDebitMapper;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitEasyCreditMapper;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitIdealMapper;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitPaydirektMapper;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitPayNowMapper;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitPayPalExpressMapper;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitPayPalMapper;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitSofortMapper;
use SprykerEco\Zed\Computop\Business\Logger\ComputopResponseLogger as ComputopLogger;
use SprykerEco\Zed\Computop\Business\Logger\LoggerInterface;
use SprykerEco\Zed\Computop\Business\Oms\Command\AuthorizeCommandHandler;
use SprykerEco\Zed\Computop\Business\Oms\Command\CancelCommandHandler;
use SprykerEco\Zed\Computop\Business\Oms\Command\CaptureCommandHandler;
use SprykerEco\Zed\Computop\Business\Oms\Command\CommandHandlerInterface;
use SprykerEco\Zed\Computop\Business\Oms\Command\Manager\AuthorizeManager;
use SprykerEco\Zed\Computop\Business\Oms\Command\Manager\CancelManager;
use SprykerEco\Zed\Computop\Business\Oms\Command\Manager\CancelManagerInterface;
use SprykerEco\Zed\Computop\Business\Oms\Command\Manager\CaptureManager;
use SprykerEco\Zed\Computop\Business\Oms\Command\Manager\ManagerInterface;
use SprykerEco\Zed\Computop\Business\Oms\Command\Manager\RefundManager;
use SprykerEco\Zed\Computop\Business\Oms\Command\RefundCommandHandler;
use SprykerEco\Zed\Computop\Business\Order\ComputopBusinessOrderFactory;
use SprykerEco\Zed\Computop\Business\Order\ComputopBusinessOrderFactoryInterface;
use SprykerEco\Zed\Computop\Business\Order\OrderManager;
use SprykerEco\Zed\Computop\Business\Order\OrderManagerInterface;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Logger\ComputopResponseLogger;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Logger\ComputopResponseLoggerInterface;
use SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\AuthorizeHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\CaptureHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\EasyCreditAuthorizeHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\HandlerInterface as PostPlaceHandlerInterface;
use SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\InquireHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\RefundHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\ReverseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\PrePlace\EasyCreditStatusHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\PrePlace\PrePlaceHandlerInterface;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\AuthorizeSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\CaptureSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Complete\CompleteResponseSaverInterface;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Complete\PayPalExpressCompleteResponseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\CreditCardResponseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\DirectDebitResponseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\EasyCreditResponseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\IdealResponseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\InitResponseSaverInterface;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\PaydirektResponseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\PayNowResponseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\PayPalExpressResponseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\PayPalResponseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\SofortResponseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\InquireSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\RefundSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\ReverseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\SaverInterface;
use SprykerEco\Zed\Computop\Business\Payment\PaymentMethodFilter;
use SprykerEco\Zed\Computop\Business\Payment\PaymentMethodFilterInterface;
use SprykerEco\Zed\Computop\Business\Payment\Reader\ComputopPaymentReader;
use SprykerEco\Zed\Computop\Business\Payment\Reader\ComputopPaymentReaderInterface;
use SprykerEco\Zed\Computop\Business\Processor\NotificationProcessor;
use SprykerEco\Zed\Computop\Business\Processor\NotificationProcessorInterface;
use SprykerEco\Zed\Computop\Business\RiskCheck\Handler\CrifHandler;
use SprykerEco\Zed\Computop\Business\RiskCheck\Handler\HandlerInterface;
use SprykerEco\Zed\Computop\Business\RiskCheck\Saver\CrifSaver;
use SprykerEco\Zed\Computop\Business\RiskCheck\Saver\RiskCheckSaverInterface;
use SprykerEco\Zed\Computop\ComputopDependencyProvider;
use SprykerEco\Zed\Computop\Dependency\ComputopToStoreInterface;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeInterface;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMessengerFacadeInterface;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMoneyFacadeInterface;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsFacadeInterface;

/**
 * @method \SprykerEco\Zed\Computop\ComputopConfig getConfig()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface getQueryContainer()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopRepositoryInterface getRepository()
 */
class ComputopBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\OrderManagerInterface
     */
    public function createOrderSaver(): OrderManagerInterface
    {
        $orderSaver = new OrderManager($this->getConfig());

        $orderSaver->registerMapper($this->createOrderFactory()->createInitCreditCardMapper());
        $orderSaver->registerMapper($this->createOrderFactory()->createInitPayNowMapper());
        $orderSaver->registerMapper($this->createOrderFactory()->createInitPayPalMapper());
        $orderSaver->registerMapper($this->createOrderFactory()->createPayPalExpressMapper());
        $orderSaver->registerMapper($this->createOrderFactory()->createInitDirectDebitMapper());
        $orderSaver->registerMapper($this->createOrderFactory()->createInitSofortMapper());
        $orderSaver->registerMapper($this->createOrderFactory()->createInitPaydirektMapper());
        $orderSaver->registerMapper($this->createOrderFactory()->createInitIdealMapper());
        $orderSaver->registerMapper($this->createOrderFactory()->createInitEasyCreditMapper());

        return $orderSaver;
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\ComputopPostSaveHookInterface
     */
    public function createPostSaveHook(): ComputopPostSaveHookInterface
    {
        $postSaveHook = new ComputopPostSaveHook($this->getConfig());
        $postSaveHook->registerMapper($this->createPostSaveSofortMapper());
        $postSaveHook->registerMapper($this->createPostSavePaydirektMapper());
        $postSaveHook->registerMapper($this->createPostSaveIdealMapper());
        $postSaveHook->registerMapper($this->createPostSaveCreditCart());
        $postSaveHook->registerMapper($this->createPostSavePayNowMapper());
        $postSaveHook->registerMapper($this->createPostSavePayPal());
        $postSaveHook->registerMapper($this->createPostSavePayPalExpress());
        $postSaveHook->registerMapper($this->createPostSaveDirectDebit());
        $postSaveHook->registerMapper($this->createPostSaveEasyCredit());

        return $postSaveHook;
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\InitResponseSaverInterface
     */
    public function createSofortResponseSaver(): InitResponseSaverInterface
    {
        return new SofortResponseSaver($this->getQueryContainer(), $this->getOmsFacade(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\InitResponseSaverInterface
     */
    public function createIdealResponseSaver(): InitResponseSaverInterface
    {
        return new IdealResponseSaver($this->getQueryContainer(), $this->getOmsFacade(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\InitResponseSaverInterface
     */
    public function createPaydirektResponseSaver(): InitResponseSaverInterface
    {
        return new PaydirektResponseSaver($this->getQueryContainer(), $this->getOmsFacade(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\InitResponseSaverInterface
     */
    public function createCreditCardResponseSaver(): InitResponseSaverInterface
    {
        return new CreditCardResponseSaver($this->getQueryContainer(), $this->getOmsFacade(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\InitResponseSaverInterface
     */
    public function createPayNowResponseSaver(): InitResponseSaverInterface
    {
        return new PayNowResponseSaver($this->getQueryContainer(), $this->getOmsFacade(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\InitResponseSaverInterface
     */
    public function createPayPalResponseSaver(): InitResponseSaverInterface
    {
        return new PayPalResponseSaver($this->getQueryContainer(), $this->getOmsFacade(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\InitResponseSaverInterface
     */
    public function createPayPalExpressResponseSaver(): InitResponseSaverInterface
    {
        return new PayPalExpressResponseSaver($this->getQueryContainer(), $this->getOmsFacade(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Complete\CompleteResponseSaverInterface
     */
    public function createPayPalExpressCompleteResponseSaver(): CompleteResponseSaverInterface
    {
        return new PayPalExpressCompleteResponseSaver(
            $this->getOmsFacade(),
            $this->getConfig(),
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\InitResponseSaverInterface
     */
    public function createDirectDebitResponseSaver(): InitResponseSaverInterface
    {
        return new DirectDebitResponseSaver($this->getQueryContainer(), $this->getOmsFacade(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\InitResponseSaverInterface
     */
    public function createEasyCreditResponseSaver(): InitResponseSaverInterface
    {
        return new EasyCreditResponseSaver($this->getQueryContainer(), $this->getOmsFacade(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Logger\ComputopResponseLoggerInterface
     */
    public function createComputopResponseLogger(): ComputopResponseLoggerInterface
    {
        return new ComputopResponseLogger();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Dependency\ComputopToStoreInterface
     */
    public function getStore(): ComputopToStoreInterface
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::STORE);
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Oms\Command\CommandHandlerInterface
     */
    public function createAuthorizeCommandHandler(): CommandHandlerInterface
    {
        return new AuthorizeCommandHandler(
            $this->createAuthorizeHandler(),
            $this->createAuthorizeManager()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Oms\Command\CommandHandlerInterface
     */
    public function createCancelCommandHandler(): CommandHandlerInterface
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
    public function createCaptureCommandHandler(): CommandHandlerInterface
    {
        return new CaptureCommandHandler(
            $this->createCaptureHandler(),
            $this->createCaptureManager()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Oms\Command\CommandHandlerInterface
     */
    public function createRefundCommandHandler(): CommandHandlerInterface
    {
        return new RefundCommandHandler(
            $this->createRefundHandler(),
            $this->createRefundManager()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Oms\Command\CommandHandlerInterface
     */
    public function createEasyCreditAuthorizeCommandHandler(): CommandHandlerInterface
    {
        return new AuthorizeCommandHandler(
            $this->createEasyCreditAuthorizeHandler(),
            $this->createAuthorizeManager()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\PrePlace\PrePlaceHandlerInterface
     */
    public function createEasyCreditStatusHandler(): PrePlaceHandlerInterface
    {
        return new EasyCreditStatusHandler(
            $this->getComputopApiFacade(),
            $this->getMoneyFacade(),
            $this->createComputopResponseLogger()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\RiskCheck\Handler\HandlerInterface
     */
    public function createCrifHandler(): HandlerInterface
    {
        return new CrifHandler(
            $this->getComputopApiFacade(),
            $this->createCrifSaver(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Logger\LoggerInterface
     */
    public function createComputopLogger(): LoggerInterface
    {
        return new ComputopLogger();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\HandlerInterface
     */
    public function createEasyCreditAuthorizeHandler(): PostPlaceHandlerInterface
    {
        return new EasyCreditAuthorizeHandler(
            $this->getComputopApiFacade(),
            $this->createAuthorizeSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Reader\ComputopPaymentReaderInterface
     */
    public function createPaymentReader(): ComputopPaymentReaderInterface
    {
        return new ComputopPaymentReader($this->getQueryContainer());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\HandlerInterface
     */
    public function createAuthorizeHandler(): PostPlaceHandlerInterface
    {
        return new AuthorizeHandler(
            $this->getComputopApiFacade(),
            $this->createAuthorizeSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\HandlerInterface
     */
    public function createReverseHandler(): PostPlaceHandlerInterface
    {
        return new ReverseHandler(
            $this->getComputopApiFacade(),
            $this->createReverseSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\HandlerInterface
     */
    public function createInquireHandler(): PostPlaceHandlerInterface
    {
        return new InquireHandler(
            $this->getComputopApiFacade(),
            $this->createInquireSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\HandlerInterface
     */
    public function createCaptureHandler(): PostPlaceHandlerInterface
    {
        return new CaptureHandler(
            $this->getComputopApiFacade(),
            $this->createCaptureSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\HandlerInterface
     */
    public function createRefundHandler(): PostPlaceHandlerInterface
    {
        return new RefundHandler(
            $this->getComputopApiFacade(),
            $this->createRefundSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\RiskCheck\Saver\RiskCheckSaverInterface
     */
    public function createCrifSaver(): RiskCheckSaverInterface
    {
        return new CrifSaver(
            $this->getQueryContainer(),
            $this->createComputopLogger(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\SaverInterface
     */
    public function createAuthorizeSaver(): SaverInterface
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
    public function createReverseSaver(): SaverInterface
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
    public function createInquireSaver(): SaverInterface
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
    public function createCaptureSaver(): SaverInterface
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
    public function createRefundSaver(): SaverInterface
    {
        return new RefundSaver(
            $this->getQueryContainer(),
            $this->createComputopResponseLogger(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Oms\Command\Manager\ManagerInterface
     */
    public function createAuthorizeManager(): ManagerInterface
    {
        return new AuthorizeManager($this->getQueryContainer(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Oms\Command\Manager\CancelManagerInterface
     */
    public function createCancelManager(): CancelManagerInterface
    {
        return new CancelManager($this->getQueryContainer(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Oms\Command\Manager\ManagerInterface
     */
    public function createCaptureManager(): ManagerInterface
    {
        return new CaptureManager($this->getQueryContainer(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Oms\Command\Manager\ManagerInterface
     */
    public function createRefundManager(): ManagerInterface
    {
        return new RefundManager($this->getQueryContainer(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\ComputopBusinessOrderFactoryInterface
     */
    public function createOrderFactory(): ComputopBusinessOrderFactoryInterface
    {
        return new ComputopBusinessOrderFactory();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface
     */
    public function createPostSaveSofortMapper(): InitMapperInterface
    {
        return new InitSofortMapper($this->getConfig(), $this->getComputopApiService());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface
     */
    public function createPostSaveCreditCart(): InitMapperInterface
    {
        return new InitCreditCardMapper($this->getConfig(), $this->getComputopApiService());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface
     */
    public function createPostSavePayNowMapper(): InitMapperInterface
    {
        return new InitPayNowMapper($this->getConfig(), $this->getComputopApiService());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface
     */
    public function createPostSavePayPal(): InitMapperInterface
    {
        return new InitPayPalMapper($this->getConfig(), $this->getComputopApiService());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface
     */
    public function createPostSavePayPalExpress(): InitMapperInterface
    {
        return new InitPayPalExpressMapper($this->getConfig(), $this->getComputopApiService());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface
     */
    public function createPostSaveDirectDebit(): InitMapperInterface
    {
        return new InitDirectDebitMapper($this->getConfig(), $this->getComputopApiService());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface
     */
    public function createPostSaveEasyCredit(): InitMapperInterface
    {
        return new InitEasyCreditMapper($this->getConfig(), $this->getComputopApiService());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface
     */
    public function createPostSavePaydirektMapper(): InitMapperInterface
    {
        return new InitPaydirektMapper($this->getConfig(), $this->getComputopApiService());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface
     */
    public function createPostSaveIdealMapper(): InitMapperInterface
    {
        return new InitIdealMapper($this->getConfig(), $this->getComputopApiService());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\PaymentMethodFilterInterface
     */
    public function createPaymentMethodFilter(): PaymentMethodFilterInterface
    {
        return new PaymentMethodFilter($this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Processor\NotificationProcessorInterface
     */
    public function createNotificationProcessor(): NotificationProcessorInterface
    {
        return new NotificationProcessor($this->getEntityManager());
    }

    /**
     * @return \SprykerEco\Service\ComputopApi\ComputopApiServiceInterface
     */
    public function getComputopApiService(): ComputopApiServiceInterface
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::SERVICE_COMPUTOP_API);
    }

    /**
     * @return \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsFacadeInterface
     */
    public function getOmsFacade(): ComputopToOmsFacadeInterface
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMessengerFacadeInterface
     */
    public function getFlashMessengerFacade(): ComputopToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::FACADE_FLASH_MESSENGER);
    }

    /**
     * @return \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMoneyFacadeInterface
     */
    public function getMoneyFacade(): ComputopToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeInterface
     */
    public function getComputopApiFacade(): ComputopToComputopApiFacadeInterface
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::FACADE_COMPUTOP_API);
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\DefaultShippingMethodQuoteExpander\QuoteDefaultShippingMethodExpanderInterface
     */
    public function createQuoteDefaultShippingMethodExpander(): QuoteDefaultShippingMethodExpanderInterface
    {
        return new QuoteDefaultShippingMethodExpander(
            $this->getConfig(),
            $this->getProvidedDependency(ComputopDependencyProvider::FACADE_SHIPMENT)
        );
    }
}
