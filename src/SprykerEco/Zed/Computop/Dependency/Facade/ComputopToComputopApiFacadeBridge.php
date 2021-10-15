<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Dependency\Facade;

use Generated\Shared\Transfer\ComputopApiCrifResponseTransfer;
use Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ComputopToComputopApiFacadeBridge implements ComputopToComputopApiFacadeInterface
{
    /**
     * @var \SprykerEco\Zed\ComputopApi\Business\ComputopApiFacadeInterface
     */
    protected $computopApiFacade;

    /**
     * @param \SprykerEco\Zed\ComputopApi\Business\ComputopApiFacadeInterface $computopApiFacade
     */
    public function __construct($computopApiFacade)
    {
        $this->computopApiFacade = $computopApiFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiEasyCreditStatusResponseTransfer
     */
    public function performEasyCreditStatusRequest(
        QuoteTransfer $quoteTransfer,
        ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
    ) {
        return $this->computopApiFacade->performEasyCreditStatusRequest($quoteTransfer, $headerApiPaymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiAuthorizeResponseTransfer
     */
    public function performEasyCreditAuthorizeRequest(
        OrderTransfer $orderTransfer,
        ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
    ) {
        return $this->computopApiFacade->performEasyCreditAuthorizeRequest($orderTransfer, $headerApiPaymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiAuthorizeResponseTransfer
     */
    public function performAuthorizationRequest(
        OrderTransfer $orderTransfer,
        ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
    ) {
        return $this->computopApiFacade->performAuthorizationRequest($orderTransfer, $headerApiPaymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiCaptureResponseTransfer
     */
    public function performCaptureRequest(
        OrderTransfer $orderTransfer,
        ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
    ) {
        return $this->computopApiFacade->performCaptureRequest($orderTransfer, $headerApiPaymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiInquireResponseTransfer
     */
    public function performInquireRequest(
        OrderTransfer $orderTransfer,
        ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
    ) {
        return $this->computopApiFacade->performInquireRequest($orderTransfer, $headerApiPaymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiRefundResponseTransfer
     */
    public function performRefundRequest(
        OrderTransfer $orderTransfer,
        ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
    ) {
        return $this->computopApiFacade->performRefundRequest($orderTransfer, $headerApiPaymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiReverseResponseTransfer
     */
    public function performReverseRequest(
        OrderTransfer $orderTransfer,
        ComputopApiHeaderPaymentTransfer $headerApiPaymentTransfer
    ) {
        return $this->computopApiFacade->performReverseRequest($orderTransfer, $headerApiPaymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiCrifResponseTransfer
     */
    public function performCrifApiCall(QuoteTransfer $quoteTransfer): ComputopApiCrifResponseTransfer
    {
        return $this->computopApiFacade->performCrifApiCall($quoteTransfer);
    }
}
