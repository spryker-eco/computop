<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business\Oms;

use ArrayObject;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use GuzzleHttp\Psr7;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem;
use SprykerEco\Service\ComputopApi\ComputopApiService;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Zed\Computop\Business\ComputopBusinessFactory;
use SprykerEco\Zed\Computop\ComputopConfig as SprykerComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeBridge;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMessengerFacadeBridge;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainer;
use SprykerEcoTest\Zed\Computop\Business\AbstractSetUpTest;

abstract class AbstractPaymentTest extends AbstractSetUpTest
{
    /**
     * @var int
     */
    public const GRAND_TOTAL = 10;

    /**
     * @var int
     */
    public const REFUND_TOTAL = 10;

    /**
     * @var int
     */
    public const SUB_TOTAL = 8;

    /**
     * @var int
     */
    public const DISCOUNT_TOTAL = 1;

    /**
     * @return string
     */
    abstract protected function getPayIdValue();

    /**
     * @return string
     */
    abstract protected function getTransIdValue();

    /**
     * @return \PHPUnit\Framework\MockObject\Builder\InvocationMocker|\SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeBridge
     */
    abstract protected function createComputopApiFacade(): ComputopToComputopApiFacadeBridge;

    /**
     * Set up DB data
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpComputop();
    }

    /**
     * @return void
     */
    protected function setUpComputop()
    {
        $this->createComputopPaymentEntity();
    }

    /**
     * @return void
     */
    protected function createComputopPaymentEntity()
    {
        $computopPaymentEntity = (new SpyPaymentComputop())
            ->setClientIp('0.0.0.0')
            ->setPaymentMethod(ComputopConfig::PAYMENT_METHOD_CREDIT_CARD)
            ->setReference($this->helper->orderEntity->getOrderReference())
            ->setFkSalesOrder($this->helper->orderEntity->getIdSalesOrder())
            ->setPayId($this->getPayIdValue())
            ->setTransId($this->getTransIdValue());
        $computopPaymentEntity->save();

        $paymentDetailEntity = new SpyPaymentComputopDetail();
        $paymentDetailEntity->setIdPaymentComputop($computopPaymentEntity->getIdPaymentComputop());
        $paymentDetailEntity->save();

        $orderItemTransfers = $this->helper->orderEntity->getItems();

        foreach ($orderItemTransfers as $orderItemTransfer) {
            $paymentOrderItemEntity = new SpyPaymentComputopOrderItem();
            $paymentOrderItemEntity
                ->setFkPaymentComputop($computopPaymentEntity->getIdPaymentComputop())
                ->setFkSalesOrderItem($orderItemTransfer->getIdSalesOrderItem());
            $paymentOrderItemEntity->setStatus(SprykerComputopConfig::OMS_STATUS_NEW);
            $paymentOrderItemEntity->save();
        }
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\ComputopBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createFactory(): ComputopBusinessFactory
    {
        $builder = $this->getMockBuilder(ComputopBusinessFactory::class);
        $builder->setMethods(
            [
                'getConfig',
                'getComputopApiService',
                'getQueryContainer',
                'getFlashMessengerFacade',
                'getComputopApiFacade',
            ],
        );

        $stub = $builder->getMock();

        $stub->method('getConfig')
            ->willReturn($this->omsHelper->createConfig());

        $stub->method('getComputopApiService')
            ->willReturn(new ComputopApiService());

        $stub->method('getQueryContainer')
            ->willReturn(new ComputopQueryContainer());

        $stub->method('getFlashMessengerFacade')
            ->willReturn($this->createMock(ComputopToMessengerFacadeBridge::class));

        $stub->method('getComputopApiFacade')
            ->willReturn($this->createComputopApiFacade());

        return $stub;
    }

    /**
     * @param string $data
     * @param int $length
     *
     * @return Psr7\Stream
     */
    protected function getStream($data, $length)
    {
        $expectedResponse = http_build_query([
            ComputopApiConfig::DATA => $data,
            ComputopApiConfig::LENGTH => $length,
        ]);
        $stream = Psr7\stream_for($expectedResponse);

        return $stream;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setIdSalesOrder($this->helper->orderEntity->getIdSalesOrder());
        $orderTransfer->setCustomer(new CustomerTransfer());
        $orderTransfer->addPayment($this->omsHelper->createPaymentTransfer());

        $totals = new TotalsTransfer();
        $totals->setGrandTotal(static::GRAND_TOTAL);
        $totals->setRefundTotal(static::REFUND_TOTAL);
        $totals->setSubtotal(static::SUB_TOTAL);
        $totals->setDiscountTotal(static::DISCOUNT_TOTAL);
        $totals->setHash('');
        $orderTransfer->setTotals($totals);
        $orderTransfer->setItems(new ArrayObject($this->omsHelper->createOrderItems()));

        return $orderTransfer;
    }
}
