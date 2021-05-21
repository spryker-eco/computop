<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\ExpressCheckout;

use Generated\Shared\Transfer\QuoteTransfer;

interface ComputopPayPalExpressPrepareHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function handle(QuoteTransfer $quoteTransfer): array;
}
