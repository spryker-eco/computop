<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Computop\Zed;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ZedRequest\Stub\ZedRequestStub;

class ComputopStub extends ZedRequestStub implements ComputopStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $responseTransfer
     *
     * @return void
     */
    public function logResponse(ComputopApiResponseHeaderTransfer $responseTransfer)
    {
        $this->zedStub->call('/computop/gateway/log-response', $responseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveSofortInitResponse(QuoteTransfer $quoteTransfer)
    {
        return $this->zedStub->call('/computop/gateway/save-sofort-init-response', $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveIdealInitResponse(QuoteTransfer $quoteTransfer)
    {
        return $this->zedStub->call('/computop/gateway/save-ideal-init-response', $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePaydirektInitResponse(QuoteTransfer $quoteTransfer)
    {
        return $this->zedStub->call('/computop/gateway/save-paydirekt-init-response', $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveCreditCardInitResponse(QuoteTransfer $quoteTransfer)
    {
        return $this->zedStub->call('/computop/gateway/save-credit-card-init-response', $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayNowInitResponse(QuoteTransfer $quoteTransfer)
    {
        return $this->zedStub->call('/computop/gateway/save-pay-now-init-response', $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayPalInitResponse(QuoteTransfer $quoteTransfer)
    {
        return $this->zedStub->call('/computop/gateway/save-pay-pal-init-response', $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveDirectDebitInitResponse(QuoteTransfer $quoteTransfer)
    {
        return $this->zedStub->call('/computop/gateway/save-direct-debit-init-response', $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveEasyCreditInitResponse(QuoteTransfer $quoteTransfer)
    {
        return $this->zedStub->call('/computop/gateway/save-easy-credit-init-response', $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function easyCreditStatusApiCall(QuoteTransfer $quoteTransfer)
    {
        return $this->zedStub->call('/computop/gateway/easy-credit-status-api-call', $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function isComputopPaymentExist(QuoteTransfer $quoteTransfer)
    {
        return $this->zedStub->call('/computop/gateway/is-computop-payment-exist', $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function performCrifApiCall(QuoteTransfer $quoteTransfer)
    {
        return $this->zedStub->call('/computop/gateway/perform-crif-api-call', $quoteTransfer);
    }
}
