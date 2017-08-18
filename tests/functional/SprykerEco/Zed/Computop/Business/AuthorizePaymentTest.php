<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Computop\Business;

use Generated\Shared\Transfer\ComputopCreditCardAuthorizeResponseTransfer;
use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\Business\ComputopFacade;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group Computop
 * @group Business
 * @group AuthorizePaymentTest
 */
class AuthorizePaymentTest extends AbstractPaymentTest
{
    const PAY_ID_VALUE = 'b5e798a99d5440e88ba487960f3f0cdc';
    const X_ID_VALUE = '09b0bf76bb4145d8bbe1bb752a736d6d';
    const TRANS_ID_VALUE = '0e1f2ee1a171fecdfa59833c2a0c0685';
    const STATUS_VALUE = 'OK';
    const CODE_VALUE = '00000000';

    /**
     * @return void
     */
    public function testAuthorizePaymentSuccess()
    {
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());

        /** @var \Generated\Shared\Transfer\ComputopCreditCardAuthorizeResponseTransfer $response */
        $response = $service->authorizationPaymentRequest($this->createOrderTransfer());

        $this->assertInstanceOf(ComputopCreditCardAuthorizeResponseTransfer::class, $response);
        $this->assertInstanceOf(ComputopResponseHeaderTransfer::class, $response->getHeader());

        $this->assertEquals(self::TRANS_ID_VALUE, $response->getHeader()->getTransId());
        $this->assertEquals(self::X_ID_VALUE, $response->getHeader()->getXId());
        $this->assertEquals(self::PAY_ID_VALUE, $response->getHeader()->getPayId());
        $this->assertEquals(self::STATUS_VALUE, $response->getHeader()->getStatus());
        $this->assertEquals(self::CODE_VALUE, $response->getHeader()->getCode());

        $this->assertTrue($response->getHeader()->getIsSuccess());
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setComputopCreditCard($this->createComputopPaymentTransfer());
        $orderTransfer->setTotals(new TotalsTransfer());
        $orderTransfer->setCustomer(new CustomerTransfer());
        return $orderTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function createComputopPaymentTransfer()
    {
        $payment = new ComputopCreditCardPaymentTransfer();
        $payment->setPaymentMethod(ComputopConstants::PAYMENT_METHOD_CREDIT_CARD);
        $payment->setPayId(self::PAY_ID_VALUE);

        return $payment;
    }

    /**
     * @return void
     */
    protected function createComputopPaymentEntity()
    {
        $computopPaymentEntity = (new SpyPaymentComputop())
            ->setClientIp('0.0.0.0')
            ->setPaymentMethod(ComputopConstants::PAYMENT_METHOD_CREDIT_CARD)
            ->setReference($this->orderEntity->getOrderReference())
            ->setFkSalesOrder($this->orderEntity->getIdSalesOrder())
            ->setPayId(self::PAY_ID_VALUE);
        $computopPaymentEntity->save();

        $paymentDetailEntity = new SpyPaymentComputopDetail();
        $paymentDetailEntity->setIdPaymentComputop($computopPaymentEntity->getIdPaymentComputop());
        $paymentDetailEntity->save();

        $orderItemTransfers = $this->orderEntity->getItems();

        foreach ($orderItemTransfers as $orderItemTransfer) {
            $paymentOrderItemEntity = new SpyPaymentComputopOrderItem();
            $paymentOrderItemEntity
                ->setFkPaymentComputop($computopPaymentEntity->getIdPaymentComputop())
                ->setFkSalesOrderItem($orderItemTransfer->getIdSalesOrderItem());
            $paymentOrderItemEntity->setStatus(ComputopConstants::COMPUTOP_OMS_STATUS_NEW);
            $paymentOrderItemEntity->save();
        }
    }

}
