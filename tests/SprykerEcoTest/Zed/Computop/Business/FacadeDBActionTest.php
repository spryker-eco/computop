<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business;

use Generated\Shared\Transfer\ComputopCreditCardInitResponseTransfer;
use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\ComputopDirectDebitInitResponseTransfer;
use Generated\Shared\Transfer\ComputopDirectDebitPaymentTransfer;
use Generated\Shared\Transfer\ComputopEasyCreditInitResponseTransfer;
use Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer;
use Generated\Shared\Transfer\ComputopApiEasyCreditStatusResponseTransfer;
use Generated\Shared\Transfer\ComputopIdealInitResponseTransfer;
use Generated\Shared\Transfer\ComputopIdealPaymentTransfer;
use Generated\Shared\Transfer\ComputopPaydirektInitResponseTransfer;
use Generated\Shared\Transfer\ComputopPaydirektPaymentTransfer;
use Generated\Shared\Transfer\ComputopPayPalInitResponseTransfer;
use Generated\Shared\Transfer\ComputopPayPalPaymentTransfer;
use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopSofortInitResponseTransfer;
use Generated\Shared\Transfer\ComputopSofortPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\Business\ComputopBusinessFactory;
use SprykerEco\Zed\Computop\Business\ComputopFacade;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeBridge;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMoneyFacadeBridge;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsFacadeBridge;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainer;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group Computop
 * @group Business
 */
class FacadeDBActionTest extends AbstractSetUpTest
{
    const METHOD_VALUE = 'METHOD';
    const PAY_ID_VALUE = 'PAY_ID_VALUE';
    const X_ID_VALUE = 'X_ID_VALUE';
    const M_ID_VALUE = 'M_ID_VALUE';
    const TRANS_ID_VALUE = 'TRANS_ID_VALUE';
    const STATUS_VALUE = 'OK';
    const CODE_VALUE = '00000000';
    const DESCRIPTION_VALUE = 'DESCRIPTION_VALUE';
    const AMOUNT_VALUE = 15000;
    const CURRENCY_VALUE = 'EUR';

    const ID_SALES_ORDER_ITEM = 1;

    /**
     * @var integer
     */
    protected $salesOrderItemId;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        /** @var \SprykerTest\Shared\Testify\Helper\ConfigHelper $configHelper */
        $configHelper = $this->getModule('\\' . ConfigHelper::class);

        $config[ComputopConstants::EASY_CREDIT_STATUS_ACTION] = 'https://www.computop-paygate.com/easyCreditDirect.aspx';

        foreach ($config as $key => $value) {
            $configHelper->setConfig($key, $value);
        }
    }

    /**
     * @return void
     */
    public function testSaveSofortInitResponse()
    {
        $this->setUpDB();
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());
        $service->saveSofortInitResponse($this->getQuoteTrasfer());

        $savedData = SpyPaymentComputopQuery::create()->findByTransId(self::TRANS_ID_VALUE)->getFirst();

        $this->assertSame(self::PAY_ID_VALUE, $savedData->getPayId());
        $this->assertSame(self::X_ID_VALUE, $savedData->getXId());
    }

    /**
     * @return void
     */
    public function testSaveIdealInitResponse()
    {
        $this->setUpDB();
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());
        $service->saveIdealInitResponse($this->getQuoteTrasfer());

        $savedData = SpyPaymentComputopQuery::create()->findByTransId(self::TRANS_ID_VALUE)->getFirst();

        $this->assertSame(self::PAY_ID_VALUE, $savedData->getPayId());
        $this->assertSame(self::X_ID_VALUE, $savedData->getXId());
    }

    /**
     * @return void
     */
    public function testSavePaydirektInitResponse()
    {
        $this->setUpDB();
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());
        $service->savePaydirektInitResponse($this->getQuoteTrasfer());

        $savedData = SpyPaymentComputopQuery::create()->findByTransId(self::TRANS_ID_VALUE)->getFirst();

        $this->assertSame(self::PAY_ID_VALUE, $savedData->getPayId());
        $this->assertSame(self::X_ID_VALUE, $savedData->getXId());
    }

    /**
     * @return void
     */
    public function testSaveCreditCardInitResponse()
    {
        $this->setUpDB();
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());
        $service->saveCreditCardInitResponse($this->getQuoteTrasfer());

        $savedData = SpyPaymentComputopQuery::create()->findByTransId(self::TRANS_ID_VALUE)->getFirst();

        $this->assertSame(self::PAY_ID_VALUE, $savedData->getPayId());
        $this->assertSame(self::X_ID_VALUE, $savedData->getXId());
    }

    /**
     * @return void
     */
    public function testSavePayPalInitResponse()
    {
        $this->setUpDB();
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());
        $service->savePayPalInitResponse($this->getQuoteTrasfer());

        $savedData = SpyPaymentComputopQuery::create()->findByTransId(self::TRANS_ID_VALUE)->getFirst();

        $this->assertSame(self::PAY_ID_VALUE, $savedData->getPayId());
        $this->assertSame(self::X_ID_VALUE, $savedData->getXId());
    }

    /**
     * @return void
     */
    public function testSaveDirectDebitInitResponse()
    {
        $this->setUpDB();
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());
        $service->saveDirectDebitInitResponse($this->getQuoteTrasfer());

        $savedData = SpyPaymentComputopQuery::create()->findByTransId(self::TRANS_ID_VALUE)->getFirst();

        $this->assertSame(self::PAY_ID_VALUE, $savedData->getPayId());
        $this->assertSame(self::X_ID_VALUE, $savedData->getXId());
    }

    /**
     * @return void
     */
    public function testSaveEasyCreditInitResponse()
    {
        $this->setUpDB();
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());
        $service->saveEasyCreditInitResponse($this->getQuoteTrasfer());

        $savedData = SpyPaymentComputopQuery::create()->findByTransId(self::TRANS_ID_VALUE)->getFirst();

        $this->assertSame(self::PAY_ID_VALUE, $savedData->getPayId());
        $this->assertSame(self::X_ID_VALUE, $savedData->getXId());
    }

    /**
     * @return void
     */
    public function testEasyCreditStatusApiCall()
    {
        $this->setUpDB();
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());
        $quote = $service->easyCreditStatusApiCall($this->getQuoteTrasfer());
        $response = $quote->getPayment()->getComputopEasyCredit()->getEasyCreditStatusResponse();
        $this->assertSame(self::PAY_ID_VALUE, $response->getHeader()->getPayId());
        $this->assertSame(self::X_ID_VALUE, $response->getHeader()->getXId());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuoteTrasfer()
    {
        $computopHeader = new ComputopApiResponseHeaderTransfer();
        $computopHeader
            ->setTransId(self::TRANS_ID_VALUE)
            ->setPayId(self::PAY_ID_VALUE)
            ->setMId(self::M_ID_VALUE)
            ->setXId(self::X_ID_VALUE)
            ->setCode(self::CODE_VALUE)
            ->setDescription(self::DESCRIPTION_VALUE)
            ->setIsSuccess(true)
            ->setStatus(self::STATUS_VALUE);

        $computopSofortInitTransfer = new ComputopSofortInitResponseTransfer();
        $computopSofortInitTransfer->setHeader($computopHeader);
        $computopSofortTransfer = new ComputopSofortPaymentTransfer();
        $computopSofortTransfer->setSofortInitResponse($computopSofortInitTransfer);

        $computopIdealInitTransfer = new ComputopIdealInitResponseTransfer();
        $computopIdealInitTransfer->setHeader($computopHeader);
        $computopIdealTransfer = new ComputopIdealPaymentTransfer();
        $computopIdealTransfer->setIdealInitResponse($computopIdealInitTransfer);

        $computopPaydirektInitTransfer = new ComputopPaydirektInitResponseTransfer();
        $computopPaydirektInitTransfer->setHeader($computopHeader);
        $computopPaydirektTransfer = new ComputopPaydirektPaymentTransfer();
        $computopPaydirektTransfer->setPaydirektInitResponse($computopPaydirektInitTransfer);

        $computopCredicCardInitTransfer = new ComputopCreditCardInitResponseTransfer();
        $computopCredicCardInitTransfer->setHeader($computopHeader);
        $computopCredicCardTransfer = new ComputopCreditCardPaymentTransfer();
        $computopCredicCardTransfer->setCreditCardInitResponse($computopCredicCardInitTransfer);

        $computopPayPalInitTransfer = new ComputopPayPalInitResponseTransfer();
        $computopPayPalInitTransfer->setHeader($computopHeader);
        $computopPayPalTransfer = new ComputopPayPalPaymentTransfer();
        $computopPayPalTransfer->setPayPalInitResponse($computopPayPalInitTransfer);

        $computopDirectDebitInitTransfer = new ComputopDirectDebitInitResponseTransfer();
        $computopDirectDebitInitTransfer->setHeader($computopHeader);
        $computopDirectDebitTransfer = new ComputopDirectDebitPaymentTransfer();
        $computopDirectDebitTransfer->setDirectDebitInitResponse($computopDirectDebitInitTransfer);

        $computopEasyCreditInitTransfer = new ComputopEasyCreditInitResponseTransfer();
        $computopEasyCreditInitTransfer->setHeader($computopHeader);
        $computopEasyCreditTransfer = new ComputopEasyCreditPaymentTransfer();
        $computopEasyCreditTransfer->setEasyCreditInitResponse($computopEasyCreditInitTransfer);

        $computopEasyCreditStatusTransfer = new ComputopApiEasyCreditStatusResponseTransfer();
        $computopEasyCreditStatusTransfer->setHeader($computopHeader);
        $computopEasyCreditTransfer->setEasyCreditStatusResponse($computopEasyCreditStatusTransfer);
        $computopEasyCreditTransfer->fromArray($computopHeader->toArray(), true);
        $computopEasyCreditTransfer->setAmount(self::AMOUNT_VALUE);
        $computopEasyCreditTransfer->setCurrency(self::CURRENCY_VALUE);

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setComputopSofort($computopSofortTransfer);
        $paymentTransfer->setComputopIdeal($computopIdealTransfer);
        $paymentTransfer->setComputopPaydirekt($computopPaydirektTransfer);
        $paymentTransfer->setComputopCreditCard($computopCredicCardTransfer);
        $paymentTransfer->setComputopPayPal($computopPayPalTransfer);
        $paymentTransfer->setComputopDirectDebit($computopDirectDebitTransfer);
        $paymentTransfer->setComputopEasyCredit($computopEasyCreditTransfer);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setPayment($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject | ComputopBusinessFactory
     */
    protected function createFactory()
    {
        $omsFacadeStub = $this->createMock(ComputopToOmsFacadeBridge::class, ['triggerEvent' => '']);
        $moneyFacadeStub = $this->createMock(ComputopToMoneyFacadeBridge::class, ['triggerEvent' => '']);
        $handler = $this->createMock(ComputopToMoneyFacadeBridge::class, ['triggerEvent' => '']);

        $builder = $this->getMockBuilder(ComputopBusinessFactory::class);
        $builder->setMethods(
            [
                'getQueryContainer',
                'getOmsFacade',
                'getConfig',
                'getMoneyFacade',
                'getComputopApiFacade',
            ]
        );

        $stub = $builder->getMock();
        $stub->method('getQueryContainer')
            ->willReturn(new ComputopQueryContainer());
        $stub->method('getOmsFacade')
            ->willReturn($omsFacadeStub);
        $stub->method('getConfig')
            ->willReturn($this->createConfig());
        $stub->method('getMoneyFacade')
            ->willReturn($moneyFacadeStub);
        $stub->method('getComputopApiFacade')
            ->willReturn($this->createComputopApiFacade());

        return $stub;
    }

    /**
     * @return \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected function createConfig()
    {
        return new ComputopConfig();
    }

    /**
     * @return void
     */
    protected function setUpDB()
    {
        $orderEntity = SpySalesOrderQuery::create()->findOne();
        $orderItemEntity = SpySalesOrderItemQuery::create()->findOne();

        $spyPaymentComputop = (new SpyPaymentComputop())
            ->setTransId('TRANS_ID_VALUE')
            ->setFkSalesOrder($orderEntity->getIdSalesOrder())
            ->setClientIp('0.0.0.0')
            ->setPaymentMethod('Test')
            ->setReference('Test');
        $spyPaymentComputop->save();

        $spyPaymentComputopDetails = (new SpyPaymentComputopDetail())
            ->setIdPaymentComputop($spyPaymentComputop->getIdPaymentComputop());
        $spyPaymentComputopDetails->save();

        $computopOrderItem = (new SpyPaymentComputopOrderItem())
            ->setFkPaymentComputop($spyPaymentComputop->getIdPaymentComputop())
            ->setFkSalesOrderItem($orderItemEntity->getIdSalesOrderItem())
            ->setStatus('test');
        $computopOrderItem->save();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeBridge
     */
    protected function createComputopApiFacade()
    {
        $stub = $this
            ->createPartialMock(
                ComputopToComputopApiFacadeBridge::class,
                ['performEasyCreditStatusRequest']
            );

        $stub->method('performEasyCreditStatusRequest')
            ->willReturn($this->createComputopEasyCreditStatusResponseTransfer());

        return $stub;
    }

    /**
    * @return \Generated\Shared\Transfer\ComputopApiEasyCreditStatusResponseTransfer
    */
    protected function createComputopEasyCreditStatusResponseTransfer()
    {
        return (new ComputopApiEasyCreditStatusResponseTransfer())
            ->setHeader(
                (new ComputopApiResponseHeaderTransfer())
                    ->setTransId(self::TRANS_ID_VALUE)
                    ->setPayId(self::PAY_ID_VALUE)
                    ->setMId(self::M_ID_VALUE)
                    ->setXId(self::X_ID_VALUE)
                    ->setCode(self::CODE_VALUE)
                    ->setDescription(self::DESCRIPTION_VALUE)
                    ->setIsSuccess(true)
                    ->setStatus(self::STATUS_VALUE)
            );
    }
}
