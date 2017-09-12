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
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerEco\Zed\Computop\Business\ComputopBusinessFactory getFactory()
 */
class ComputopFacade extends AbstractFacade implements ComputopFacadeInterface
{

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $orderSaver = $this->getFactory()->createOrderSaver();

        $orderSaver->registerMapper($this->getFactory()->createOrderCreditCardMapper());
        $orderSaver->registerMapper($this->getFactory()->createOrderPayPalMapper());
        $orderSaver->registerMapper($this->getFactory()->createOrderDirectDebitMapper());

        $orderSaver->saveOrderPayment($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop $savedComputopEntity
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function authorizationPaymentRequest(OrderTransfer $orderTransfer, SpyPaymentComputop $savedComputopEntity)
    {
        $paymentMethod = $this->getFactory()->getPaymentMethod($orderTransfer);

        $computopResponseTransfer = $this
            ->getFactory()
            ->createAuthorizationPaymentRequest($paymentMethod, $savedComputopEntity)
            ->request($orderTransfer);

        $this
            ->getFactory()
            ->createAuthorizeResponseHandler()
            ->handle($computopResponseTransfer, $orderTransfer);

        return $computopResponseTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop $savedComputopEntity
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function inquirePaymentRequest(OrderTransfer $orderTransfer, SpyPaymentComputop $savedComputopEntity)
    {
        $paymentMethod = $this->getFactory()->getPaymentMethod($orderTransfer);
        ;

        $computopResponseTransfer = $this
            ->getFactory()
            ->createInquirePaymentRequest($paymentMethod, $savedComputopEntity)
            ->request($orderTransfer);

        $this
            ->getFactory()
            ->createInquireResponseHandler()
            ->handle($computopResponseTransfer, $orderTransfer);

        return $computopResponseTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop $savedComputopEntity
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function reversePaymentRequest(OrderTransfer $orderTransfer, SpyPaymentComputop $savedComputopEntity)
    {
        $paymentMethod = $this->getFactory()->getPaymentMethod($orderTransfer);
        ;

        $computopResponseTransfer = $this
            ->getFactory()
            ->createReversePaymentRequest($paymentMethod, $savedComputopEntity)
            ->request($orderTransfer);

        $this
            ->getFactory()
            ->createReverseResponseHandler()
            ->handle($computopResponseTransfer, $orderTransfer);

        return $computopResponseTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return void
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
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop $savedComputopEntity
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function capturePaymentRequest(OrderTransfer $orderTransfer, SpyPaymentComputop $savedComputopEntity)
    {
        $paymentMethod = $this->getFactory()->getPaymentMethod($orderTransfer);
        ;

        $computopResponseTransfer = $this
            ->getFactory()
            ->createCapturePaymentRequest($paymentMethod, $savedComputopEntity)
            ->request($orderTransfer);

        $this
            ->getFactory()
            ->createCaptureResponseHandler()
            ->handle($computopResponseTransfer, $orderTransfer);

        return $computopResponseTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop $savedComputopEntity
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function refundPaymentRequest(OrderTransfer $orderTransfer, SpyPaymentComputop $savedComputopEntity)
    {
        $paymentMethod = $this->getFactory()->getPaymentMethod($orderTransfer);
        ;

        $computopResponseTransfer = $this
            ->getFactory()
            ->createRefundPaymentRequest($paymentMethod, $savedComputopEntity)
            ->request($orderTransfer);

        $this
            ->getFactory()
            ->createRefundResponseHandler()
            ->handle($computopResponseTransfer, $orderTransfer);

        return $computopResponseTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     * @param string $method
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function logResponseHeader(ComputopResponseHeaderTransfer $header, $method)
    {
        $this->getFactory()->createComputopResponseLogger()->log($header, $method);

        return $header;
    }

}
