<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Computop\Expander;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteShipmentExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithDefaultShippingMethod(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
