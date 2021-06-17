<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Computop;

use Generated\Shared\Transfer\QuoteTransfer;

class ComputopQuoteDefaultShipmentExpander implements ComputopQuoteDefaultShipmentExpanderInterface
{
    /**
     * @var \SprykerEco\Client\Computop\ComputopClientInterface
     */
    protected $computopClient;

    /**
     * @param \SprykerEco\Client\Computop\ComputopClientInterface $computopClient
     */
    public function __construct(ComputopClientInterface $computopClient)
    {
        $this->computopClient = $computopClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithDefaultShippingMethod(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (count($quoteTransfer->getItems()) === 0 || $this->quoteAlreadyHasShipment($quoteTransfer)) {
            return $quoteTransfer;
        }

        return $this->computopClient->expandQuoteWithDefaultShippingMethod($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function quoteAlreadyHasShipment(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemShipmentTransfer = $itemTransfer->getShipment();
            if ($itemShipmentTransfer !== null) {
                //at least one item already has shipment method
                return true;
            }
        }

        return false;
    }
}
