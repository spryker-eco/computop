<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Zed\Computop\Business\Hook\ComputopPostSaveHook;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitDirectDebitMapper;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitEasyCreditMapper;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitIdealMapper;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitPaydirektMapper;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitPayNowMapper;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitPayPalMapper;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitSofortMapper;
use SprykerEco\Zed\Computop\Business\Logger\ComputopResponseLogger as ComputopLogger;
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
use SprykerEco\Zed\Computop\Business\Payment\Handler\Logger\ComputopResponseLogger;
use SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\AuthorizeHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\CaptureHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\InquireHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\RefundHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\ReverseHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\PrePlace\EasyCreditStatusHandler;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\AuthorizeSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\CaptureSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\CreditCardResponseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\DirectDebitResponseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\EasyCreditResponseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\IdealResponseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\PaydirektResponseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\PayNowResponseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\PayPalResponseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\SofortResponseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\InquireSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\RefundSaver;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\ReverseSaver;
use SprykerEco\Zed\Computop\Business\Payment\Reader\ComputopPaymentReader;
use SprykerEco\Zed\Computop\Business\RiskCheck\Handler\CrifHandler;
use SprykerEco\Zed\Computop\Business\RiskCheck\Saver\CrifSaver;
use SprykerEco\Zed\Computop\ComputopDependencyProvider;

/**
 * @method \SprykerEco\Zed\Computop\ComputopConfig getConfig()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface getQueryContainer()
 */
class ComputopBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\OrderManagerInterface
     */
    public function createOrderSaver()
    {
        $orderSaver = new OrderManager($this->getConfig());

        $orderSaver->registerMapper($this->createOrderFactory()->createInitCreditCardMapper());
        $orderSaver->registerMapper($this->createOrderFactory()->createInitPayNowMapper());
        $orderSaver->registerMapper($this->createOrderFactory()->createInitPayPalMapper());
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
    public function createPostSaveHook()
    {
        $postSaveHook = new ComputopPostSaveHook($this->getConfig());
        $postSaveHook->registerMapper($this->createPostSaveSofortMapper());
        $postSaveHook->registerMapper($this->createPostSavePaydirektMapper());
        $postSaveHook->registerMapper($this->createPostSaveIdealMapper());
        $postSaveHook->registerMapper($this->createPostSaveCreditCart());
        $postSaveHook->registerMapper($this->createPostSavePayNowMapper());
        $postSaveHook->registerMapper($this->createPostSavePayPal());
        $postSaveHook->registerMapper($this->createPostSaveDirectDebit());
        $postSaveHook->registerMapper($this->createPostSaveEasyCredit());

        return $postSaveHook;
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
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\InitResponseSaverInterface
     */
    public function createCreditCardResponseSaver()
    {
        return new CreditCardResponseSaver($this->getQueryContainer(), $this->getOmsFacade(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\InitResponseSaverInterface
     */
    public function createPayNowResponseSaver()
    {
        return new PayNowResponseSaver($this->getQueryContainer(), $this->getOmsFacade(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\InitResponseSaverInterface
     */
    public function createPayPalResponseSaver()
    {
        return new PayPalResponseSaver($this->getQueryContainer(), $this->getOmsFacade(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\InitResponseSaverInterface
     */
    public function createDirectDebitResponseSaver()
    {
        return new DirectDebitResponseSaver($this->getQueryContainer(), $this->getOmsFacade(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init\InitResponseSaverInterface
     */
    public function createEasyCreditResponseSaver()
    {
        return new EasyCreditResponseSaver($this->getQueryContainer(), $this->getOmsFacade(), $this->getConfig());
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
     * @return \SprykerEco\Zed\Computop\Business\Oms\Command\CommandHandlerInterface
     */
    public function createEasyCreditAuthorizeCommandHandler()
    {
        return new AuthorizeCommandHandler(
            $this->createEasyCreditAuthorizeHandler(),
            $this->createAuthorizeManager()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\PrePlace\PrePlaceHandlerInterface
     */
    public function createEasyCreditStatusHandler()
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
    public function createCrifHandler()
    {
        return new CrifHandler(
            $this->createApiFactory()->createCrifRequest(),
            $this->createCrifSaver()

        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Logger\LoggerInterface
     */
    public function createComputopLogger()
    {
        return new ComputopLogger();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\HandlerInterface
     */
    public function createEasyCreditAuthorizeHandler()
    {
        return new AuthorizeHandler(
            $this->getComputopApiFacade(),
            $this->createAuthorizeSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Reader\ComputopPaymentReaderInterface
     */
    public function createPaymentReader()
    {
        return new ComputopPaymentReader($this->getQueryContainer());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\HandlerInterface
     */
    protected function createAuthorizeHandler()
    {
        return new AuthorizeHandler(
            $this->getComputopApiFacade(),
            $this->createAuthorizeSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\HandlerInterface
     */
    protected function createReverseHandler()
    {
        return new ReverseHandler(
            $this->getComputopApiFacade(),
            $this->createReverseSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\HandlerInterface
     */
    protected function createInquireHandler()
    {
        return new InquireHandler(
            $this->getComputopApiFacade(),
            $this->createInquireSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\HandlerInterface
     */
    protected function createCaptureHandler()
    {
        return new CaptureHandler(
            $this->getComputopApiFacade(),
            $this->createCaptureSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\HandlerInterface
     */
    protected function createRefundHandler()
    {
        return new RefundHandler(
            $this->getComputopApiFacade(),
            $this->createRefundSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\RiskCheck\Saver\RiskCheckSaverInterface
     */
    protected function createCrifSaver()
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
    protected function createAuthorizeSaver()
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
    protected function createReverseSaver()
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
    protected function createInquireSaver()
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
    protected function createCaptureSaver()
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
    protected function createRefundSaver()
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
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface
     */
    protected function createPostSaveSofortMapper()
    {
        return new InitSofortMapper($this->getConfig(), $this->getComputopApiService());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface
     */
    protected function createPostSaveCreditCart()
    {
        return new InitCreditCardMapper($this->getConfig(), $this->getComputopApiService());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface
     */
    protected function createPostSavePayNowMapper()
    {
        return new InitPayNowMapper($this->getConfig(), $this->getComputopApiService());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface
     */
    protected function createPostSavePayPal()
    {
        return new InitPayPalMapper($this->getConfig(), $this->getComputopApiService());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface
     */
    protected function createPostSaveDirectDebit()
    {
        return new InitDirectDebitMapper($this->getConfig(), $this->getComputopApiService());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface
     */
    protected function createPostSaveEasyCredit()
    {
        return new InitEasyCreditMapper($this->getConfig(), $this->getComputopApiService());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface
     */
    protected function createPostSavePaydirektMapper()
    {
        return new InitPaydirektMapper($this->getConfig(), $this->getComputopApiService());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface
     */
    protected function createPostSaveIdealMapper()
    {
        return new InitIdealMapper($this->getConfig(), $this->getComputopApiService());
    }

    /**
     * @return \SprykerEco\Service\ComputopApi\ComputopApiServiceInterface
     */
    protected function getComputopApiService()
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::SERVICE_COMPUTOP_API);
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
        return $this->getProvidedDependency(ComputopDependencyProvider::FACADE_FLASH_MESSENGER);
    }

    /**
     * @return \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMoneyFacadeInterface
     */
    protected function getMoneyFacade()
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeInterface
     */
    protected function getComputopApiFacade()
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::FACADE_COMPUTOP_API);
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\PaymentMethodFilterInterface
     */
    public function createPaymentMethodFilter()
    {
        return new PaymentMethodFilter($this->getConfig());
    }
}
