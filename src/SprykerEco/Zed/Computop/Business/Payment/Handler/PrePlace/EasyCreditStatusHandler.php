<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\PrePlace;

use Generated\Shared\Transfer\QuoteTransfer;

class EasyCreditStatusHandler extends AbstractHandler
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function handle(QuoteTransfer $quoteTransfer)
    {
        $computopHeaderPayment = $this->createComputopHeaderPayment($quoteTransfer);
        $responseTransfer = $this->request->request($quoteTransfer, $computopHeaderPayment);
        
        return $responseTransfer;
    }
}
