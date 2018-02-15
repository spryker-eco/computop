<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Computop\Zed;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ZedRequest\Stub\ZedRequestStub;

class ComputopStub extends ZedRequestStub implements ComputopStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $responseTransfer
     *
     * @return void
     */
    public function logResponse(ComputopResponseHeaderTransfer $responseTransfer)
    {
        $this->zedStub->call('/computop/gateway/log-response', $responseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveSofortInitResponse(QuoteTransfer $quoteTransfer)
    {
        $this->zedStub->call('/computop/gateway/save-sofort-init-response', $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveIdealInitResponse(QuoteTransfer $quoteTransfer)
    {
        $this->zedStub->call('/computop/gateway/save-ideal-init-response', $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function savePaydirektInitResponse(QuoteTransfer $quoteTransfer)
    {
        $this->zedStub->call('/computop/gateway/save-paydirekt-init-response', $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveCreditCardInitResponse(QuoteTransfer $quoteTransfer)
    {
        $this->zedStub->call('/computop/gateway/save-credit-card-init-response', $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function savePayPalInitResponse(QuoteTransfer $quoteTransfer)
    {
        $this->zedStub->call('/computop/gateway/save-pay-pal-init-response', $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveDirectDebitInitResponse(QuoteTransfer $quoteTransfer)
    {
        $this->zedStub->call('/computop/gateway/save-direct-debit-init-response', $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveEasyCreditInitResponse(QuoteTransfer $quoteTransfer)
    {
        $this->zedStub->call('/computop/gateway/save-easy-credit-init-response', $quoteTransfer);
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
}
