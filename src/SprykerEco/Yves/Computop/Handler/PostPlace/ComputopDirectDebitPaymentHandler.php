<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\PostPlace;

use Generated\Shared\Transfer\ComputopDirectDebitPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class ComputopDirectDebitPaymentHandler extends AbstractPostPlacePaymentHandler
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addPaymentToQuote(QuoteTransfer $quoteTransfer, AbstractTransfer $responseTransfer)
    {
        if ($quoteTransfer->getPayment()->getComputopDirectDebit() === null) {
            $computopTransfer = new ComputopDirectDebitPaymentTransfer();
            $quoteTransfer->getPayment()->setComputopDirectDebit($computopTransfer);
        }

        /** @var \Generated\Shared\Transfer\ComputopDirectDebitInitResponseTransfer $responseTransfer */
        $quoteTransfer->getPayment()->getComputopDirectDebit()->setDirectDebitInitResponse(
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
        return $this->computopClient->saveDirectDebitInitResponse($quoteTransfer);
    }
}
