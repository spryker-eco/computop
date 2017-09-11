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

interface ComputopFacadeInterface
{

    /**
     * Specification:
     * - Saves order payment method data according to quote and checkout response transfer data.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop $savedComputopEntity
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function authorizationPaymentRequest(OrderTransfer $orderTransfer, SpyPaymentComputop $savedComputopEntity);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop $savedComputopEntity
     *
     * @return \Generated\Shared\Transfer\ComputopInquireResponseTransfer
     */
    public function inquirePaymentRequest(OrderTransfer $orderTransfer, SpyPaymentComputop $savedComputopEntity);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop $savedComputopEntity
     *
     * @return \Generated\Shared\Transfer\ComputopReverseResponseTransfer
     */
    public function reversePaymentRequest(OrderTransfer $orderTransfer, SpyPaymentComputop $savedComputopEntity);

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return void
     */
    public function cancelPaymentItem(SpySalesOrderItem $orderItem);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop $savedComputopEntity
     *
     * @return \Generated\Shared\Transfer\ComputopCaptureResponseTransfer
     */
    public function capturePaymentRequest(OrderTransfer $orderTransfer, SpyPaymentComputop $savedComputopEntity);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop $savedComputopEntity
     *
     * @return \Generated\Shared\Transfer\ComputopRefundResponseTransfer
     */
    public function refundPaymentRequest(OrderTransfer $orderTransfer, SpyPaymentComputop $savedComputopEntity);

    /**
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     * @param string $method
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function logResponseHeader(ComputopResponseHeaderTransfer $header, $method);

}
