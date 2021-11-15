<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\PostPlace;

use Generated\Shared\Transfer\ComputopPayNowPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class ComputopPayNowPaymentHandler extends AbstractPostPlacePaymentHandler
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ComputopPayNowInitResponseTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addPaymentToQuote(QuoteTransfer $quoteTransfer, AbstractTransfer $responseTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getPayment()->getComputopPayNow() === null) {
            $computopTransfer = new ComputopPayNowPaymentTransfer();
            $quoteTransfer->getPayment()->setComputopPayNow($computopTransfer);
        }

        /** @var \Generated\Shared\Transfer\ComputopPayNowInitResponseTransfer $responseTransfer */
        $quoteTransfer->getPayment()->getComputopPayNow()->setPayNowInitResponse(
            $responseTransfer,
        );

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function saveInitResponse(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->computopClient->savePayNowInitResponse($quoteTransfer);
    }
}
