<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Order;

use ArrayObject;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use SprykerEco\Shared\Computop\ComputopConstants;

class OrderManager implements OrderManagerInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        if ($quoteTransfer->getPayment()->getPaymentProvider() !== ComputopConstants::PROVIDER_NAME) {
            return;
        }

        $this->handleDatabaseTransaction(function () use ($quoteTransfer, $checkoutResponseTransfer) {

            $paymentEntity = $this->savePaymentForOrder(
                $quoteTransfer->getPayment(),
                $checkoutResponseTransfer->getSaveOrder()
            );

            $this->savePaymentDetailForOrder(
                $quoteTransfer->getPayment(),
                $paymentEntity
            );

            $this->savePaymentForOrderItems(
                $checkoutResponseTransfer->getSaveOrder()->getOrderItems(),
                $paymentEntity->getIdPaymentComputop()
            );
        });
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputop
     */
    protected function savePaymentForOrder(PaymentTransfer $paymentTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        $paymentEntity = new SpyPaymentComputop();

        $paymentEntity->setClientIp($paymentTransfer->getComputopCreditCard()->getClientIp());
        $paymentEntity->setPaymentMethod($paymentTransfer->getPaymentMethod());
        $paymentEntity->setReference($saveOrderTransfer->getOrderReference());
        $paymentEntity->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder());
        $paymentEntity->setTransId($paymentTransfer->getComputopCreditCard()->getTransId());
        $paymentEntity->setXId($paymentTransfer->getComputopCreditCard()->getCreditCardOrderResponse()->getHeader()->getXId());
        $paymentEntity->setPayId($paymentTransfer->getComputopCreditCard()->getCreditCardOrderResponse()->getHeader()->getPayId());
        $paymentEntity->setPcnr($paymentTransfer->getComputopCreditCard()->getCreditCardOrderResponse()->getPCNr());

        $paymentEntity->save();

        return $paymentEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop $paymentEntity
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail
     */
    protected function savePaymentDetailForOrder(PaymentTransfer $paymentTransfer, SpyPaymentComputop $paymentEntity)
    {
        $paymentDetailEntity = new SpyPaymentComputopDetail();

        $paymentDetailEntity->fromArray($paymentTransfer->getComputopCreditCard()->getCreditCardOrderResponse()->toArray());
        $paymentDetailEntity->setIdPaymentComputop($paymentEntity->getIdPaymentComputop());
        $paymentDetailEntity->save();

        return $paymentDetailEntity;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $orderItemTransfers
     * @param int $idPayment
     *
     * @return void
     */
    protected function savePaymentForOrderItems(ArrayObject $orderItemTransfers, $idPayment)
    {
        foreach ($orderItemTransfers as $orderItemTransfer) {
            $paymentOrderItemEntity = new SpyPaymentComputopOrderItem();

            $paymentOrderItemEntity
                ->setFkPaymentComputop($idPayment)
                ->setFkSalesOrderItem($orderItemTransfer->getIdSalesOrderItem());
            $paymentOrderItemEntity->setStatus(ComputopConstants::COMPUTOP_OMS_STATUS_NEW);

            $paymentOrderItemEntity->save();
        }
    }

}
