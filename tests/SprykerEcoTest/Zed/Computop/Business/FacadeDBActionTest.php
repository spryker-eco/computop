<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business;

use ArrayObject;
use Generated\Shared\Transfer\ComputopApiCrifResponseTransfer;
use Generated\Shared\Transfer\ComputopApiEasyCreditStatusResponseTransfer;
use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopCreditCardInitResponseTransfer;
use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\ComputopCrifTransfer;
use Generated\Shared\Transfer\ComputopDirectDebitInitResponseTransfer;
use Generated\Shared\Transfer\ComputopDirectDebitPaymentTransfer;
use Generated\Shared\Transfer\ComputopEasyCreditInitResponseTransfer;
use Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer;
use Generated\Shared\Transfer\ComputopIdealInitResponseTransfer;
use Generated\Shared\Transfer\ComputopIdealPaymentTransfer;
use Generated\Shared\Transfer\ComputopPaydirektInitResponseTransfer;
use Generated\Shared\Transfer\ComputopPaydirektPaymentTransfer;
use Generated\Shared\Transfer\ComputopPayNowInitResponseTransfer;
use Generated\Shared\Transfer\ComputopPayNowPaymentTransfer;
use Generated\Shared\Transfer\ComputopPayPalInitResponseTransfer;
use Generated\Shared\Transfer\ComputopPayPalPaymentTransfer;
use Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer;
use Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer;
use Generated\Shared\Transfer\ComputopSofortInitResponseTransfer;
use Generated\Shared\Transfer\ComputopSofortPaymentTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\Business\ComputopBusinessFactory;
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
    public const METHOD_VALUE = 'METHOD';
    public const PAY_ID_VALUE = 'PAY_ID_VALUE';
    public const X_ID_VALUE = 'X_ID_VALUE';
    public const M_ID_VALUE = 'M_ID_VALUE';
    public const TRANS_ID_VALUE = 'TRANS_ID_VALUE';
    public const STATUS_VALUE = 'OK';
    public const CODE_VALUE = '00000000';
    public const DESCRIPTION_VALUE = 'DESCRIPTION_VALUE';
    public const AMOUNT_VALUE = 15000;
    public const CURRENCY_VALUE = 'EUR';

    public const ID_SALES_ORDER_ITEM = 1;

    public const STATUS_VALUE_SUCCESS = 'SUCCESS';
    public const CRIF_GREEN_RESULT = 'GREEN';

    /**
     * @var int
     */
    protected $salesOrderItemId;

    /**
     * @var \SprykerEcoTest\Zed\Computop\ComputopZedTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
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

        /** @var \SprykerEco\Zed\Computop\Business\ComputopFacade $facade */
        $facade = $this->tester->getFacade();
        $facade->setFactory($this->createFactory());

        $facade->saveSofortInitResponse($this->getQuoteTrasfer());

        $this->checkSavedSpyPaymentMethod();
    }

    /**
     * @return void
     */
    public function testSaveIdealInitResponse()
    {
        $this->setUpDB();
        /** @var \SprykerEco\Zed\Computop\Business\ComputopFacade $facade */
        $facade = $this->tester->getFacade();
        $facade->setFactory($this->createFactory());

        $facade->saveIdealInitResponse($this->getQuoteTrasfer());

        $this->checkSavedSpyPaymentMethod();
    }

    /**
     * @return void
     */
    public function testSavePaydirektInitResponse()
    {
        $this->setUpDB();

        /** @var \SprykerEco\Zed\Computop\Business\ComputopFacade $facade */
        $facade = $this->tester->getFacade();
        $facade->setFactory($this->createFactory());

        $facade->savePaydirektInitResponse($this->getQuoteTrasfer());

        $this->checkSavedSpyPaymentMethod();
    }

    /**
     * @return void
     */
    public function testSavePayuCeeSingleInitResponse()
    {
        $this->setUpDB();

        /** @var \SprykerEco\Zed\Computop\Business\ComputopFacade $facade */
        $facade = $this->tester->getFacade();
        $facade->setFactory($this->createFactory());

        $facade->savePayuCeeSingleInitResponse($this->getQuoteTrasfer());

        $this->checkSavedSpyPaymentMethod();
    }

    /**
     * @return void
     */
    public function testSaveCreditCardInitResponse()
    {
        $this->setUpDB();

        /** @var \SprykerEco\Zed\Computop\Business\ComputopFacade $facade */
        $facade = $this->tester->getFacade();
        $facade->setFactory($this->createFactory());

        $facade->saveCreditCardInitResponse($this->getQuoteTrasfer());

        $this->checkSavedSpyPaymentMethod();
    }

    /**
     * @return void
     */
    public function testSavePayNowInitResponse()
    {
        $this->setUpDB();

        /** @var \SprykerEco\Zed\Computop\Business\ComputopFacade $facade */
        $facade = $this->tester->getFacade();
        $facade->setFactory($this->createFactory());

        $facade->savePayNowInitResponse($this->getQuoteTrasfer());

        $this->checkSavedSpyPaymentMethod();
    }

    /**
     * @return void
     */
    public function testSavePayPalInitResponse()
    {
        $this->setUpDB();

        /** @var \SprykerEco\Zed\Computop\Business\ComputopFacade $facade */
        $facade = $this->tester->getFacade();
        $facade->setFactory($this->createFactory());

        $facade->savePayPalInitResponse($this->getQuoteTrasfer());

        $this->checkSavedSpyPaymentMethod();
    }

    /**
     * @return void
     */
    public function testSaveDirectDebitInitResponse()
    {
        $this->setUpDB();

        /** @var \SprykerEco\Zed\Computop\Business\ComputopFacade $facade */
        $facade = $this->tester->getFacade();
        $facade->setFactory($this->createFactory());

        $facade->saveDirectDebitInitResponse($this->getQuoteTrasfer());

        $this->checkSavedSpyPaymentMethod();
    }

    /**
     * @return void
     */
    public function testSaveEasyCreditInitResponse()
    {
        $this->setUpDB();

        /** @var \SprykerEco\Zed\Computop\Business\ComputopFacade $facade */
        $facade = $this->tester->getFacade();
        $facade->setFactory($this->createFactory());

        $facade->saveEasyCreditInitResponse($this->getQuoteTrasfer());

        $this->checkSavedSpyPaymentMethod();
    }

    /**
     * @return void
     */
    public function testEasyCreditStatusApiCall()
    {
        $this->setUpDB();

        /** @var \SprykerEco\Zed\Computop\Business\ComputopFacade $facade */
        $facade = $this->tester->getFacade();
        $facade->setFactory($this->createFactory());

        $quote = $facade->easyCreditStatusApiCall($this->getQuoteTrasfer());
        $response = $quote->getPayment()->getComputopEasyCredit()->getEasyCreditStatusResponse();
        $this->assertSame(self::PAY_ID_VALUE, $response->getHeader()->getPayId());
        $this->assertSame(self::X_ID_VALUE, $response->getHeader()->getXId());
    }

    /**
     * @return void
     */
    public function testIsComputopPaymentExistSuccess()
    {
        $this->setUpDB();

        /** @var \SprykerEco\Zed\Computop\Business\ComputopFacade $facade */
        $facade = $this->tester->getFacade();
        $facade->setFactory($this->createFactory());

        $response = $facade->isComputopPaymentExist($this->getQuoteTrasfer());

        $this->assertTrue($response->getPayment()->getIsComputopPaymentExist());
    }

    /**
     * @return void
     */
    public function testIsComputopPaymentExistFailure()
    {
        $this->setUpDB();

        /** @var \SprykerEco\Zed\Computop\Business\ComputopFacade $facade */
        $facade = $this->tester->getFacade();
        $facade->setFactory($this->createFactory());

        $quoteTransfer = $this->getQuoteTrasfer();
        $quoteTransfer->getPayment()->getComputopPayNow()->setTransId('FAILURE_TRANS_VALUE');
        $response = $facade->isComputopPaymentExist($quoteTransfer);

        $this->assertNotTrue($response->getPayment()->getIsComputopPaymentExist());
    }

    /**
     * @return void
     */
    public function testPerformCrifApiCall()
    {
        $this->setUpDB();

        /** @var \SprykerEco\Zed\Computop\Business\ComputopFacade $facade */
        $facade = $this->tester->getFacade();
        $facade->setFactory($this->createFactory());

        $quoteTransfer = $this->getQuoteTrasfer();
        $response = $facade->performCrifApiCall($quoteTransfer);

        $this->assertInstanceOf(ComputopCrifTransfer::class, $response->getComputopCrif());
        $this->assertNotEmpty($response->getComputopCrif()->getResult());
        $this->assertNotEmpty($response->getComputopCrif()->getStatus());
        $this->assertNotEmpty($response->getComputopCrif()->getCode());
        $this->assertNotEmpty($response->getComputopCrif()->getDescription());
    }

    /**
     * @return void
     */
    public function testFilterPaymentMethods()
    {
        $this->setUpDB();

        /** @var \SprykerEco\Zed\Computop\Business\ComputopFacade $facade */
        $facade = $this->tester->getFacade();
        $facade->setFactory($this->createFactory());

        $quoteTransfer = $this->getQuoteTrasfer();
        $response = $facade->filterPaymentMethods($this->getPaymentMethodsTransfer(), $quoteTransfer);

        $this->assertInstanceOf(PaymentMethodsTransfer::class, $response);
        $this->assertGreaterThanOrEqual(1, $response->getMethods()->count());
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    protected function getPaymentMethodsTransfer()
    {
        $methods = new ArrayObject();
        $methods->append((new PaymentMethodTransfer())->setMethodName(ComputopSharedConfig::PAYMENT_METHOD_CREDIT_CARD));
        $methods->append((new PaymentMethodTransfer())->setMethodName(ComputopSharedConfig::PAYMENT_METHOD_SOFORT));
        $methods->append((new PaymentMethodTransfer())->setMethodName(ComputopSharedConfig::PAYMENT_METHOD_PAYDIREKT));
        $methods->append((new PaymentMethodTransfer())->setMethodName(ComputopSharedConfig::PAYMENT_METHOD_PAY_PAL));
        $methods->append((new PaymentMethodTransfer())->setMethodName(ComputopSharedConfig::PAYMENT_METHOD_PAY_NOW));
        $methods->append((new PaymentMethodTransfer())->setMethodName(ComputopSharedConfig::PAYMENT_METHOD_IDEAL));
        $methods->append((new PaymentMethodTransfer())->setMethodName(ComputopSharedConfig::PAYMENT_METHOD_DIRECT_DEBIT));
        $methods->append((new PaymentMethodTransfer())->setMethodName(ComputopSharedConfig::PAYMENT_METHOD_EASY_CREDIT));
        $methods->append((new PaymentMethodTransfer())->setMethodName(ComputopSharedConfig::PAYMENT_METHOD_PAYU_CEE_SINGLE));

        return (new PaymentMethodsTransfer())->setMethods($methods);
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

        $computopPayuCeeSingleInitTransfer = new ComputopPayuCeeSingleInitResponseTransfer();
        $computopPayuCeeSingleInitTransfer->setHeader($computopHeader);
        $computopPayuCeeSingleTransfer = new ComputopPayuCeeSinglePaymentTransfer();
        $computopPayuCeeSingleTransfer->setPayuCeeSingleInitResponse($computopPayuCeeSingleInitTransfer);

        $computopCredicCardInitTransfer = new ComputopCreditCardInitResponseTransfer();
        $computopCredicCardInitTransfer->setHeader($computopHeader);
        $computopCredicCardTransfer = new ComputopCreditCardPaymentTransfer();
        $computopCredicCardTransfer->setCreditCardInitResponse($computopCredicCardInitTransfer);

        $computopPayNowInitTransfer = new ComputopPayNowInitResponseTransfer();
        $computopPayNowInitTransfer->setHeader($computopHeader);
        $computopPayNowTransfer = new ComputopPayNowPaymentTransfer();
        $computopPayNowTransfer->setPayNowInitResponse($computopPayNowInitTransfer);
        $computopPayNowTransfer->setTransId('TRANS_ID_VALUE');

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
        $paymentTransfer->setComputopPayNow($computopPayNowTransfer);
        $paymentTransfer->setComputopPayPal($computopPayPalTransfer);
        $paymentTransfer->setComputopDirectDebit($computopDirectDebitTransfer);
        $paymentTransfer->setComputopEasyCredit($computopEasyCreditTransfer);
        $paymentTransfer->setComputopPayuCeeSingle($computopPayuCeeSingleTransfer);
        $paymentTransfer->setPaymentSelection(ComputopSharedConfig::PAYMENT_METHOD_PAY_NOW);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setPayment($paymentTransfer);

        $quoteTransfer->setComputopCrif($this->createComputopCrifTransfer());

        return $quoteTransfer;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject | ComputopBusinessFactory
     */
    protected function createFactory()
    {
        $omsFacadeStub = $this->createMock(ComputopToOmsFacadeBridge::class, ['triggerEvent' => '']);
        $moneyFacadeStub = $this->createMock(ComputopToMoneyFacadeBridge::class, ['triggerEvent' => '']);
        $moneyFacadeStub->method('convertDecimalToInteger')
            ->willReturn(10.35);

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
    protected function createComputopApiFacade(): ComputopToComputopApiFacadeBridge
    {
        $stub = $this
            ->createPartialMock(
                ComputopToComputopApiFacadeBridge::class,
                [
                    'performEasyCreditStatusRequest',
                    'performCrifApiCall',
                ]
            );

        $stub->method('performEasyCreditStatusRequest')
            ->willReturn($this->createComputopEasyCreditStatusResponseTransfer());

        $stub->method('performCrifApiCall')
            ->willReturn($this->createComputopApiCrifResponseTransfer());

        return $stub;
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopApiEasyCreditStatusResponseTransfer
     */
    protected function createComputopEasyCreditStatusResponseTransfer()
    {
        return (new ComputopApiEasyCreditStatusResponseTransfer())
            ->setHeader($this->createComputopApiResponseHeaderTransfer())
            ->setDecision('eyJ3c01lc3NhZ2VzIjp7Im1lc3NhZ2VzIjpbXX0sInV1aWQiOiJkYzY1ZTU3My0yYTg1LTQ4NjQtYTVlYS1lZGQ4ZDE0NGNmN2MiLCJlbnRzY2hlaWR1bmciOnsiZW50c2NoZWlkdW5nc2VyZ2VibmlzIjoiR1JVRU4iLCJ0ZXh0YmF1c3RlaW4iOm51bGwsImxhdWZ6ZWl0Z3JlbnplbiI6eyJtaW5pbWFsZUxhdWZ6ZWl0Ijo2LCJtYXhpbWFsZUxhdWZ6ZWl0IjoxMn19fQ')
            ->setFinancing('eyJ3c01lc3NhZ2VzIjp7Im1lc3NhZ2VzIjpbXX0sInV1aWQiOiIzMTZmMWYxNi1mZmQ1LTQwYmEtYTc4OS1mZDU5NGE2MzcyZTAiLCJyYXRlbnBsYW4iOnsiZ2VzYW10c3VtbWUiOiIyNDUuNTEiLCJ6aW5zZW4iOnsiZWZmZWt0aXZ6aW5zIjoiOC41NCIsIm5vbWluYWx6aW5zIjoiOC4yMiIsImFuZmFsbGVuZGVaaW5zZW4iOiIxMC4zNSJ9LCJ6YWhsdW5nc3BsYW4iOnsiYW56YWhsUmF0ZW4iOjEyLCJ0ZXJtaW5FcnN0ZVJhdGUiOjE1MzgzNDQ4MDAwMDAsInRlcm1pbkxldHp0ZVJhdGUiOjE1NjcyODg4MDAwMDAsImJldHJhZ1JhdGUiOiIyMS4wMCIsImJldHJhZ0xldHp0ZVJhdGUiOiIxNC41MSJ9fSwibGF1ZnplaXRncmVuemVuIjp7Im1pbmltYWxlTGF1ZnplaXQiOjYsIm1heGltYWxlTGF1ZnplaXQiOjEyfSwiZmluYW56aWVydW5nIjp7ImJlc3RlbGx3ZXJ0IjoiMjM1LjE2IiwibGF1ZnplaXQiOjEyfSwidGlsZ3VuZ3NwbGFuVGV4dCI6IlRpbGd1bmdzcGxhbjogTGF1ZnplaXQgMTIgTW9uYXRlLCBtb25hdGxpY2hlIFJhdGUgMjEsMDAgRVVSIChlcnN0ZSBSYXRlOiAxOSwzOSBFVVIgVGlsZ3VuZywgMSw2MSBFVVIgWmluc2VuKSwgU2NobHVzc3JhdGUgMTQsNTEgRVVSICgxNCw0MSBFVVIgVGlsZ3VuZywgMCwxMCBFVVIgWmluc2VuKS4gQWx0ZXJuYXRpdmVyIFRpbGd1bmdzcGxhbjogTGF1ZnplaXQgOSBNb25hdGUsIG1vbmF0bGljaGUgUmF0ZSAyOCwwMCBFVVIgKGVyc3RlIFJhdGU6IDI2LDM5IEVVUiBUaWxndW5nLCAxLDYxIEVVUiBaaW5zZW4pLCBTY2hsdXNzcmF0ZSAxOSwwNCBFVVIgKDE4LDkxIEVVUiBUaWxndW5nLCAwLDEzIEVVUiBaaW5zZW4pLiJ9')
            ->setProcess('eyJ3c01lc3NhZ2VzIjp7Im1lc3NhZ2VzIjpbXX0sInV1aWQiOiI0YzkwMDM2My1kMjQyLTRhY2MtYmZhNi00ZjY2ZjZkMWZmNjEiLCJhbGxnZW1laW5lVm9yZ2FuZ3NkYXRlbiI6eyJzaG9wS2VubnVuZyI6IjIuZGUuOTk5OS4xMDAwMyIsInRiVm9yZ2FuZ3NrZW5udW5nIjoiYjk1NmYxNTcuMDgxNDE2NDgxNXVWd3NTRWcyWWlBczN6d3o5V0RDSzdMNSIsInNob3BWb3JnYW5nc2tlbm51bmciOm51bGwsImZhY2hsaWNoZVZvcmdhbmdza2VubnVuZyI6IlRLTlVIRCIsImRldmljZUlkZW50VG9rZW4iOiI4ZmIxMjA1Zi05MWM3LTRiYTEtODNkNC0xMGVkMGM0YzNiNDYiLCJzdGF0dXMiOiJFTlRTQ0hJRURFTiIsInVybFZvcnZlcnRyYWdsaWNoZUluZm9ybWF0aW9uZW4iOiJodHRwczovL3JhdGVua2F1Zi5lYXN5Y3JlZGl0LmRlL3BheW1lbnQvIy9iOTU2ZjE1Ny4wODE0MTY0ODE1dVZ3c1NFZzJZaUFzM3p3ejlXRENLN0w1L3ZvcnZlcnRyYWdsaWNoZWluZm8iLCJoYWVuZGxlcnNwZXppZmlzY2hlclppbnNzYXR6IjoiOC41NCIsIm1vZWdsaWNoZVJhdGVucGxhZW5lIjpbeyJnZXNhbXRzdW1tZSI6IjI0MC43NCIsInppbnNlbiI6eyJlZmZla3RpdnppbnMiOiI4LjU0Iiwibm9taW5hbHppbnMiOiI4LjIyIiwiYW5mYWxsZW5kZVppbnNlbiI6IjUuNTgifSwiemFobHVuZ3NwbGFuIjp7ImFuemFobFJhdGVuIjo2LCJ0ZXJtaW5FcnN0ZVJhdGUiOjE1MzgzNDQ4MDAwMDAsInRlcm1pbkxldHp0ZVJhdGUiOjE1NTEzOTQ4MDAwMDAsImJldHJhZ1JhdGUiOiI0MS4wMCIsImJldHJhZ0xldHp0ZVJhdGUiOiIzNS43NCJ9fSx7Imdlc2FtdHN1bW1lIjoiMjQzLjA0Iiwiemluc2VuIjp7ImVmZmVrdGl2emlucyI6IjguNTQiLCJub21pbmFsemlucyI6IjguMjEiLCJhbmZhbGxlbmRlWmluc2VuIjoiNy44OCJ9LCJ6YWhsdW5nc3BsYW4iOnsiYW56YWhsUmF0ZW4iOjksInRlcm1pbkVyc3RlUmF0ZSI6MTUzODM0NDgwMDAwMCwidGVybWluTGV0enRlUmF0ZSI6MTU1OTM0MDAwMDAwMCwiYmV0cmFnUmF0ZSI6IjI4LjAwIiwiYmV0cmFnTGV0enRlUmF0ZSI6IjE5LjA0In19LHsiZ2VzYW10c3VtbWUiOiIyNDUuNTEiLCJ6aW5zZW4iOnsiZWZmZWt0aXZ6aW5zIjoiOC41NCIsIm5vbWluYWx6aW5zIjoiOC4yMiIsImFuZmFsbGVuZGVaaW5zZW4iOiIxMC4zNSJ9LCJ6YWhsdW5nc3BsYW4iOnsiYW56YWhsUmF0ZW4iOjEyLCJ0ZXJtaW5FcnN0ZVJhdGUiOjE1MzgzNDQ4MDAwMDAsInRlcm1pbkxldHp0ZVJhdGUiOjE1NjcyODg4MDAwMDAsImJldHJhZ1JhdGUiOiIyMS4wMCIsImJldHJhZ0xldHp0ZVJhdGUiOiIxNC41MSJ9fV19LCJ0aWxndW5nc3BsYW5UZXh0IjoiVGlsZ3VuZ3NwbGFuOiBMYXVmemVpdCAxMiBNb25hdGUsIG1vbmF0bGljaGUgUmF0ZSAyMSwwMCBFVVIgKGVyc3RlIFJhdGU6IDE5LDM5IEVVUiBUaWxndW5nLCAxLDYxIEVVUiBaaW5zZW4pLCBTY2hsdXNzcmF0ZSAxNCw1MSBFVVIgKDE0LDQxIEVVUiBUaWxndW5nLCAwLDEwIEVVUiBaaW5zZW4pLiBBbHRlcm5hdGl2ZXIgVGlsZ3VuZ3NwbGFuOiBMYXVmemVpdCA5IE1vbmF0ZSwgbW9uYXRsaWNoZSBSYXRlIDI4LDAwIEVVUiAoZXJzdGUgUmF0ZTogMjYsMzkgRVVSIFRpbGd1bmcsIDEsNjEgRVVSIFppbnNlbiksIFNjaGx1c3NyYXRlIDE5LDA0IEVVUiAoMTgsOTEgRVVSIFRpbGd1bmcsIDAsMTMgRVVSIFppbnNlbikuIn0');
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopApiCrifResponseTransfer
     */
    protected function createComputopApiCrifResponseTransfer()
    {
        return (new ComputopApiCrifResponseTransfer())
            ->setHeader($this->createComputopApiResponseHeaderTransfer())
            ->setCode(static::CODE_VALUE)
            ->setResult(static::CRIF_GREEN_RESULT)
            ->setStatus(static::STATUS_VALUE)
            ->setDescription(static::STATUS_VALUE_SUCCESS);
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer
     */
    protected function createComputopApiResponseHeaderTransfer()
    {
        return (new ComputopApiResponseHeaderTransfer())
            ->setTransId(static::TRANS_ID_VALUE)
            ->setPayId(static::PAY_ID_VALUE)
            ->setMId(static::M_ID_VALUE)
            ->setXId(static::X_ID_VALUE)
            ->setCode(static::CODE_VALUE)
            ->setDescription(static::DESCRIPTION_VALUE)
            ->setIsSuccess(true)
            ->setStatus(static::STATUS_VALUE);
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopCrifTransfer
     */
    protected function createComputopCrifTransfer()
    {
        return (new ComputopCrifTransfer())
            ->setCode(static::CODE_VALUE)
            ->setResult(static::CRIF_GREEN_RESULT)
            ->setStatus(static::STATUS_VALUE)
            ->setDescription(static::STATUS_VALUE_SUCCESS);
    }

    /**
     * @param string $id
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputop
     */
    private function getSpyPaymentComputopByID($id): SpyPaymentComputop
    {
        return SpyPaymentComputopQuery::create()->findByTransId($id)->getFirst();
    }

    /**
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputop
     */
    private function checkSavedSpyPaymentMethod(): SpyPaymentComputop
    {
        $spyPayment = $this->getSpyPaymentComputopByID(self::TRANS_ID_VALUE);
        $this->assertSame(self::PAY_ID_VALUE, $spyPayment->getPayId());
        $this->assertSame(self::X_ID_VALUE, $spyPayment->getXId());

        return $spyPayment;
    }
}
