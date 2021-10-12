<?php

namespace SprykerEco\Yves\Computop\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

interface PayPalExpressInitPluginInterface
{
    /**
     * Specification:
     * - Aggregates additional data for PayPal Express payment.
     *
     * @api
     *
     * @param QuoteTransfer $quoteTransfer
     *
     * @return QuoteTransfer
     */
    public function aggregate(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
