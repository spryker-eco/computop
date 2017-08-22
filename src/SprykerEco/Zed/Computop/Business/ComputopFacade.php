<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerEco\Zed\Computop\Business\ComputopBusinessFactory getFactory()
 */
class ComputopFacade extends AbstractFacade implements ComputopFacadeInterface
{

    /**
     * {@inheritdoc}
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this
            ->getFactory()
            ->createOrderSaver()
            ->saveOrderPayment($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * {@inheritdoc}
     */
    public function authorizationPaymentRequest(OrderTransfer $orderTransfer)
    {
        $paymentMethod = $orderTransfer->getComputopCreditCard()->getPaymentMethod();

        $computopResponseTransfer = $this
            ->getFactory()
            ->createAuthorizationPaymentRequest($paymentMethod)
            ->request($orderTransfer);

        $this
            ->getFactory()
            ->createAuthorizeResponseHandler()
            ->handle($computopResponseTransfer, $orderTransfer);

        return $computopResponseTransfer;
    }

    /**
     * {@inheritdoc}
     */
    public function inquirePaymentRequest(OrderTransfer $orderTransfer)
    {
        $paymentMethod = $orderTransfer->getComputopCreditCard()->getPaymentMethod();

        $computopResponseTransfer = $this
            ->getFactory()
            ->createInquirePaymentRequest($paymentMethod)
            ->request($orderTransfer);

        $this
            ->getFactory()
            ->createInquireResponseHandler()
            ->handle($computopResponseTransfer, $orderTransfer);

        return $computopResponseTransfer;
    }

    /**
     * {@inheritdoc}
     */
    public function reversePaymentRequest(OrderTransfer $orderTransfer)
    {
        $paymentMethod = $orderTransfer->getComputopCreditCard()->getPaymentMethod();

        $computopResponseTransfer = $this
            ->getFactory()
            ->createReversePaymentRequest($paymentMethod)
            ->request($orderTransfer);

        $this
            ->getFactory()
            ->createReverseResponseHandler()
            ->handle($computopResponseTransfer, $orderTransfer);

        return $computopResponseTransfer;
    }

    /**
     * {@inheritdoc}
     */
    public function cancelPaymentItem(SpySalesOrderItem $orderItem)
    {
        $this
            ->getFactory()
            ->createCancelItemManager()
            ->changeComputopItemStatus($orderItem);
    }

    /**
     * {@inheritdoc
     */
    public function capturePaymentRequest(OrderTransfer $orderTransfer)
    {
        $paymentMethod = $orderTransfer->getComputopCreditCard()->getPaymentMethod();

        $computopResponseTransfer = $this
            ->getFactory()
            ->createCapturePaymentRequest($paymentMethod)
            ->request($orderTransfer);

        $this
            ->getFactory()
            ->createCaptureResponseHandler()
            ->handle($computopResponseTransfer, $orderTransfer);

        return $computopResponseTransfer;
    }

    /**
     * {@inheritdoc}
     */
    public function refundPaymentRequest(OrderTransfer $orderTransfer)
    {
        $paymentMethod = $orderTransfer->getComputopCreditCard()->getPaymentMethod();

        $computopResponseTransfer = $this
            ->getFactory()
            ->createRefundPaymentRequest($paymentMethod)
            ->request($orderTransfer);

        $this
            ->getFactory()
            ->createRefundResponseHandler()
            ->handle($computopResponseTransfer, $orderTransfer);

        return $computopResponseTransfer;
    }

    /**
     * {@inheritdoc}
     */
    public function logResponseHeader(ComputopResponseHeaderTransfer $header, $method)
    {
        $this->getFactory()->createComputopResponseLogger()->log($header, $method);

        return $header;
    }

}
