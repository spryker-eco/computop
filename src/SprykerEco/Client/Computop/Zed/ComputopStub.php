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
     * @param \Generated\Shared\Transfer\QuoteTransfer $responseTransfer
     *
     * @return void
     */
    public function saveSofortInitResponse(QuoteTransfer $responseTransfer)
    {
        $this->zedStub->call('/computop/gateway/save-sofort-init-response', $responseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $responseTransfer
     *
     * @return void
     */
    public function saveIdealInitResponse(QuoteTransfer $responseTransfer)
    {
        $this->zedStub->call('/computop/gateway/save-ideal-init-response', $responseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $responseTransfer
     *
     * @return void
     */
    public function savePaydirektInitResponse(QuoteTransfer $responseTransfer)
    {
        $this->zedStub->call('/computop/gateway/save-paydirekt-init-response', $responseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function easyCreditStatusApiCall(QuoteTransfer $quoteTransfer)
    {
        $this->zedStub->call('/computop/gateway/easy-credit-status-api-call', $quoteTransfer);
    }
}
