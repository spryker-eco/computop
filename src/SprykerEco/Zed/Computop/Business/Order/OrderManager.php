<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Order;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
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
        if ($quoteTransfer->getPayment()->getPaymentProvider() === ComputopConstants::PROVIDER_NAME) {
            $this->handleDatabaseTransaction(function () use ($quoteTransfer, $checkoutResponseTransfer) {
                $this->savePaymentForOrder(
                    $quoteTransfer->getPayment(),
                    $checkoutResponseTransfer->getSaveOrder()
                );
            });
        }
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
        $paymentEntity->setXid($paymentTransfer->getComputopCreditCard()->getCreditCardResponse()->getXid());
        $paymentEntity->setPayId($paymentTransfer->getComputopCreditCard()->getCreditCardResponse()->getPayId());
        $paymentEntity->setPcnr($paymentTransfer->getComputopCreditCard()->getCreditCardResponse()->getPcnr());

        $paymentEntity->save();

        return $paymentEntity;
    }

}
