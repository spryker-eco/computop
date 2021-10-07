<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Computop\Expander;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Client\Computop\Zed\ComputopStubInterface;

class QuoteDefaultShipmentExpander implements QuoteShipmentExpanderInterface
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
    public function expand(QuoteTransfer $quoteTransfer): QuoteTransfer
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
        if ($quoteTransfer->getShipment() === null) {
            return false;
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemShipmentTransfer = $itemTransfer->getShipment();
            if ($itemShipmentTransfer !== null) {
                return true;
            }
        }

        return false;
    }
}
