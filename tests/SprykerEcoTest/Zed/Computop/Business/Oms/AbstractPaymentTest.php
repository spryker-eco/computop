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
use SprykerEco\Service\Computop\ComputopService;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Zed\Computop\Business\ComputopBusinessFactory;
use SprykerEco\Zed\Computop\ComputopConfig as SprykerComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMessengerFacadeBridge;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainer;
use SprykerEcoTest\Zed\Computop\Business\AbstractSetUpTest;

abstract class AbstractPaymentTest extends AbstractSetUpTest
{
    const GRAND_TOTAL = 10;
    const REFUND_TOTAL = 10;
    const SUB_TOTAL = 8;
    const DISCOUNT_TOTAL = 1;

    /**
     * @return string
     */
    abstract protected function getPayIdValue();

    /**
     * @return string
     */
    abstract protected function getTransIdValue();

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    abstract protected function getApiAdapterStub();

    /**
     * Set up DB data
     *
     * @return void
     */
    public function setUp()
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
     * @return \PHPUnit_Framework_MockObject_MockObject | ComputopBusinessFactory
     */
    protected function createFactory()
    {
        $builder = $this->getMockBuilder(ComputopBusinessFactory::class);
        $builder->setMethods(
            [
                'getConfig',
                'getComputopService',
                'getQueryContainer',
                'createApiFactory',
                'getFlashMessengerFacade',
            ]
        );

        $stub = $builder->getMock();

        $stub->method('getConfig')
            ->willReturn($this->omsHelper->createConfig());

        $stub->method('getComputopService')
            ->willReturn(new ComputopService());

        $stub->method('getQueryContainer')
            ->willReturn(new ComputopQueryContainer());

        $stub->method('createApiFactory')
            ->willReturn($this->getApiAdapterStub());

        $stub->method('getFlashMessengerFacade')
            ->willReturn($this->createMock(ComputopToMessengerFacadeBridge::class));

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
        $orderTransfer->setTotals(new TotalsTransfer());
        $orderTransfer->setCustomer(new CustomerTransfer());
        $orderTransfer->addPayment($this->omsHelper->createPaymentTransfer());

        $totals = new TotalsTransfer();
        $totals->setGrandTotal(self::GRAND_TOTAL);
        $totals->setRefundTotal(self::REFUND_TOTAL);
        $totals->setSubtotal(self::SUB_TOTAL);
        $totals->setDiscountTotal(self::DISCOUNT_TOTAL);
        $orderTransfer->setTotals($totals);
        $orderTransfer->setItems(new ArrayObject($this->omsHelper->createOrderItems()));

        return $orderTransfer;
    }
}
