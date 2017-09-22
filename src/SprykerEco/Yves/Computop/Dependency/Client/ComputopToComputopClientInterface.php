<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Dependency\Client;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ComputopToComputopClientInterface
{

    /**
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $cardPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function logResponse(ComputopResponseHeaderTransfer $cardPaymentTransfer);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveSofortResponse(QuoteTransfer $quoteTransfer);

}
