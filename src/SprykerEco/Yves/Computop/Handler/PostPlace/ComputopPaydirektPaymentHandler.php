<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\PostPlace;

use Generated\Shared\Transfer\ComputopPaydirektPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class ComputopPaydirektPaymentHandler extends AbstractPostPlacePaymentHandler
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ComputopPaydirektInitResponseTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addPaymentToQuote(QuoteTransfer $quoteTransfer, AbstractTransfer $responseTransfer)
    {
        if ($quoteTransfer->getPayment()->getComputopPaydirekt() === null) {
            $computopTransfer = new ComputopPaydirektPaymentTransfer();
            $quoteTransfer->getPayment()->setComputopPaydirekt($computopTransfer);
        }

        $quoteTransfer->getPayment()->getComputopPaydirekt()->setPaydirektInitResponse(
            $responseTransfer
        );

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function saveInitResponse(QuoteTransfer $quoteTransfer)
    {
        return $this->computopClient->savePaydirektInitResponse($quoteTransfer);
    }
}
