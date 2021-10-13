<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function aggregate(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
