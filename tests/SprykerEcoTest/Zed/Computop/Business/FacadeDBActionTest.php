<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business;

use Generated\Shared\Transfer\ComputopIdealInitResponseTransfer;
use Generated\Shared\Transfer\ComputopIdealPaymentTransfer;
use Generated\Shared\Transfer\ComputopPaydirektInitResponseTransfer;
use Generated\Shared\Transfer\ComputopPaydirektPaymentTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopSofortInitResponseTransfer;
use Generated\Shared\Transfer\ComputopSofortPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItemQuery;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use SprykerEco\Zed\Computop\Business\ComputopBusinessFactory;
use SprykerEco\Zed\Computop\Business\ComputopFacade;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsFacadeBridge;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainer;

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

    const ID_SALES_ORDER_ITEM = 1;

    /**
     * @var integer
     */
    protected $salesOrderItemId;

    /**
     * @return void
     */
    public function testSaveSofortResponse()
    {
        $this->setUpDB();
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());
        $service->saveSofortResponse($this->getQuoteTrasfer());

        $savedData = SpyPaymentComputopQuery::create()->findByTransId(self::TRANS_ID_VALUE)->getFirst();

        $this->assertSame(self::PAY_ID_VALUE, $savedData->getPayId());
        $this->assertSame(self::X_ID_VALUE, $savedData->getXId());
    }

    /**
     * @return void
     */
    public function testSaveIdealResponse()
    {
        $this->setUpDB();
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());
        $service->saveIdealResponse($this->getQuoteTrasfer());

        $savedData = SpyPaymentComputopQuery::create()->findByTransId(self::TRANS_ID_VALUE)->getFirst();

        $this->assertSame(self::PAY_ID_VALUE, $savedData->getPayId());
        $this->assertSame(self::X_ID_VALUE, $savedData->getXId());
    }

    /**
     * @return void
     */
    public function testSavePaydirektResponse()
    {
        $this->setUpDB();
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());
        $service->savePaydirektResponse($this->getQuoteTrasfer());

        $savedData = SpyPaymentComputopQuery::create()->findByTransId(self::TRANS_ID_VALUE)->getFirst();

        $this->assertSame(self::PAY_ID_VALUE, $savedData->getPayId());
        $this->assertSame(self::X_ID_VALUE, $savedData->getXId());
    }

    /**
     * @return void
     */
    public function testCancelPaymentItems()
    {
        $this->setUpDB();
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());
        //todo: update test
//        $service->cancelPaymentItems($this->getPaymentItems());
//        $computopItem = SpyPaymentComputopOrderItemQuery::create()->findByFkSalesOrderItem($this->salesOrderItemId)->getFirst();
//
//        $this->assertSame($computopItem->getStatus(), 'cancelled');
    }

    /**
     * @return array
     */
    protected function getPaymentItems()
    {
        $item = SpyPaymentComputopOrderItemQuery::create()->findOne();
        $this->salesOrderItemId = $item->getFkSalesOrderItem();

        $paymentItem = new SpySalesOrderItem();
        $paymentItem->setIdSalesOrderItem($this->salesOrderItemId);

        return [$paymentItem];
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuoteTrasfer()
    {
        $computopHeader = new ComputopResponseHeaderTransfer();
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

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setComputopSofort($computopSofortTransfer);
        $paymentTransfer->setComputopIdeal($computopIdealTransfer);
        $paymentTransfer->setComputopPaydirekt($computopPaydirektTransfer);

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

        $builder = $this->getMockBuilder(ComputopBusinessFactory::class);
        $builder->setMethods(
            [
                'getQueryContainer',
                'getOmsFacade',
                'getConfig',
            ]
        );

        $stub = $builder->getMock();
        $stub->method('getQueryContainer')
            ->willReturn(new ComputopQueryContainer());

        $stub->method('getOmsFacade')
            ->willReturn($omsFacadeStub);

        $stub->method('getConfig')
            ->willReturn($this->createConfig());

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
}
