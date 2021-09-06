<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Computop;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Client\Computop\Zed\ComputopStubInterface;

class ComputopQuoteDefaultShipmentExpander implements ComputopQuoteDefaultShipmentExpanderInterface
{
    /**
     * @var \SprykerEco\Client\Computop\Zed\ComputopStubInterface $computopStub
     */
    protected $computopStub;

    /**
     * @param \SprykerEco\Client\Computop\Zed\ComputopStubInterface $computopStub
     */
    public function __construct(ComputopStubInterface $computopStub)
    {
        $this->computopStub = $computopStub;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithDefaultShippingMethod(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getItems()->count() === 0 || $this->isQuoteHasShipment($quoteTransfer)) {
            return $quoteTransfer;
        }

        return $this->computopStub->expandQuoteWithDefaultShippingMethod($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteHasShipment(QuoteTransfer $quoteTransfer): bool
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