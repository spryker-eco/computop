<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Computop\Zed;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ZedRequest\Stub\ZedRequestStub;

class ComputopStub extends ZedRequestStub
{
    /**
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
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
    public function saveSofortResponse(QuoteTransfer $responseTransfer)
    {
        $this->zedStub->call('/computop/gateway/save-sofort-response', $responseTransfer);
    }
}
