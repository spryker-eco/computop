<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Dependency\Facade;

use Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer;
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
    );

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiAuthorizeResponseTransfer
     */
    public function performAuthorizationRequest(
        OrderTransfer $orderTransfer,
        ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
    );

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiCaptureResponseTransfer
     */
    public function performCaptureRequest(
        OrderTransfer $orderTransfer,
        ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
    );

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiInquireResponseTransfer
     */
    public function performInquireRequest(
        OrderTransfer $orderTransfer,
        ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
    );

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiRefundResponseTransfer
     */
    public function performRefundRequest(
        OrderTransfer $orderTransfer,
        ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
    );

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiReverseResponseTransfer
     */
    public function performReverseRequest(
        OrderTransfer $orderTransfer,
        ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
    );

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiCrifResponseTransfer
     */
    public function performCrifApiCall(QuoteTransfer $quoteTransfer);
}
