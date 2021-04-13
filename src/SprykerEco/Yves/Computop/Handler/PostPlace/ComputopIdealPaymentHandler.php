<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\PostPlace;

use Generated\Shared\Transfer\ComputopIdealPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class ComputopIdealPaymentHandler extends AbstractPostPlacePaymentHandler
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addPaymentToQuote(QuoteTransfer $quoteTransfer, AbstractTransfer $responseTransfer)
    {
        if ($quoteTransfer->getPayment()->getComputopIdeal() === null) {
            $computopTransfer = new ComputopIdealPaymentTransfer();
            $quoteTransfer->getPayment()->setComputopIdeal($computopTransfer);
        }
        /** @var \Generated\Shared\Transfer\ComputopIdealInitResponseTransfer $responseTransfer */
        $quoteTransfer->getPayment()->getComputopIdeal()->setIdealInitResponse(
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
        return $this->computopClient->saveIdealInitResponse($quoteTransfer);
    }
}
