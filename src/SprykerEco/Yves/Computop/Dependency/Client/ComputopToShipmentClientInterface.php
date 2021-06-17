<?php

namespace SprykerEco\Yves\Computop\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

interface ComputopToShipmentClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithShipmentGroups(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
