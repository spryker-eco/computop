<?php

namespace SprykerEco\Yves\Computop\Mapper\Init\PrePlace;

use Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface PayPalExpressToQuoteMapperInterface
{
    /**
     * @param QuoteTransfer $quoteTransfer
     * @param ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
     *
     * @return QuoteTransfer
     */
    public function mapAddressTransfer(
        QuoteTransfer $quoteTransfer,
        ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
    ): QuoteTransfer;

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
     *
     * @return QuoteTransfer
     */
    public function mapBillingTransfer(
        QuoteTransfer $quoteTransfer,
        ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
    ): QuoteTransfer;

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
     *
     * @return QuoteTransfer
     */
    public function mapCustomerTransfer(
        QuoteTransfer $quoteTransfer,
        ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
    ): QuoteTransfer;
}
