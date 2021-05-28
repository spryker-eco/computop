<?php

namespace SprykerEco\Yves\Computop\Dependency\Client;

use Generated\Shared\Transfer\ComputopApiPayPalExpressCompleteResponseTransfer;
use Generated\Shared\Transfer\ComputopApiPayPalExpressPrepareResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ComputopToComputopApiClientInterface
{
    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return QuoteTransfer
     */
    public function sendPayPalExpressPrepareRequest(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return QuoteTransfer
     */
    public function sendPayPalExpressCompleteRequest(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
