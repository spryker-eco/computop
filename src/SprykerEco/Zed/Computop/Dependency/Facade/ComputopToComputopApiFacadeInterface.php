<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Dependency\Facade;

use Generated\Shared\Transfer\ComputopApiAuthorizeResponseTransfer;
use Generated\Shared\Transfer\ComputopApiCaptureResponseTransfer;
use Generated\Shared\Transfer\ComputopApiCrifResponseTransfer;
use Generated\Shared\Transfer\ComputopApiEasyCreditStatusResponseTransfer;
use Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer;
use Generated\Shared\Transfer\ComputopApiInquireResponseTransfer;
use Generated\Shared\Transfer\ComputopApiRefundResponseTransfer;
use Generated\Shared\Transfer\ComputopApiReverseResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ComputopToComputopApiFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiEasyCreditStatusResponseTransfer
     */
    public function performEasyCreditStatusRequest(
        QuoteTransfer $quoteTransfer,
        ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
    ): ComputopApiEasyCreditStatusResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiAuthorizeResponseTransfer
     */
    public function performEasyCreditAuthorizeRequest(
        OrderTransfer $orderTransfer,
        ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
    ): ComputopApiAuthorizeResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiAuthorizeResponseTransfer
     */
    public function performAuthorizationRequest(
        OrderTransfer $orderTransfer,
        ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
    ): ComputopApiAuthorizeResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiCaptureResponseTransfer
     */
    public function performCaptureRequest(
        OrderTransfer $orderTransfer,
        ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
    ): ComputopApiCaptureResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiInquireResponseTransfer
     */
    public function performInquireRequest(
        OrderTransfer $orderTransfer,
        ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
    ): ComputopApiInquireResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiRefundResponseTransfer
     */
    public function performRefundRequest(
        OrderTransfer $orderTransfer,
        ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
    ): ComputopApiRefundResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiReverseResponseTransfer
     */
    public function performReverseRequest(
        OrderTransfer $orderTransfer,
        ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
    ): ComputopApiReverseResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiCrifResponseTransfer
     */
    public function performCrifApiCall(QuoteTransfer $quoteTransfer): ComputopApiCrifResponseTransfer;
}
