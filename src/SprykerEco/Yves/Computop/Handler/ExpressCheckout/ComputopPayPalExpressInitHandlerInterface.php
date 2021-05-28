<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\ExpressCheckout;

use Generated\Shared\Transfer\QuoteTransfer;

interface ComputopPayPalExpressInitHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $responseArray
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handle(QuoteTransfer $quoteTransfer, array $responseArray): QuoteTransfer;
}
