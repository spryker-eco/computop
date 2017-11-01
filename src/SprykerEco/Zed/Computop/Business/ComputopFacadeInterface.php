<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ComputopHeaderPaymentTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ComputopFacadeInterface
{
    /**
     * Specification:
     * - Saves order payment method data according to quote and checkout response transfer data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer);

    /**
     * Specification:
     * - Makes Authorization API call.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function authorizationPaymentRequest(OrderTransfer $orderTransfer, ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * Specification:
     * - Makes Inquire API call.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \Generated\Shared\Transfer\ComputopInquireResponseTransfer
     */
    public function inquirePaymentRequest(OrderTransfer $orderTransfer, ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * Specification:
     * - Makes Reverse API call.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \Generated\Shared\Transfer\ComputopReverseResponseTransfer
     */
    public function reversePaymentRequest(OrderTransfer $orderTransfer, ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * Specification:
     * - Cancel payment items in DB.
     *
     * @api
     *
     * @param array $orderItems
     *
     * @return void
     */
    public function cancelPaymentItems(array $orderItems);

    /**
     * Specification:
     * - Makes Capture API call.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \Generated\Shared\Transfer\ComputopCaptureResponseTransfer
     */
    public function capturePaymentRequest(OrderTransfer $orderTransfer, ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * Specification:
     * - Makes Refund API call.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \Generated\Shared\Transfer\ComputopRefundResponseTransfer
     */
    public function refundPaymentRequest(OrderTransfer $orderTransfer, ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * Specification:
     * - Saves Response header to DB.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     * @param string $method
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function logResponseHeader(ComputopResponseHeaderTransfer $header, $method);

    /**
     * Specification:
     * - Saves Sofort Response to DB.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveSofortResponse(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Saves IDeal Response to DB.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveIdealResponse(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Saves Paydirekt Response to DB.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePaydirektResponse(QuoteTransfer $quoteTransfer);
}
