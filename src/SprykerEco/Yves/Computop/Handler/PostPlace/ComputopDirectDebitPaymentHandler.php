<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\PostPlace;

use Generated\Shared\Transfer\ComputopDirectDebitPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
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
        if ($quoteTransfer->getPayment() === null) {
            $paymentTransfer = new PaymentTransfer();
            $quoteTransfer->setPayment($paymentTransfer);
        }

        if ($quoteTransfer->getPayment()->getComputopDirectDebit() === null) {
            $computopTransfer = new ComputopDirectDebitPaymentTransfer();
            $quoteTransfer->getPayment()->setComputopDirectDebit($computopTransfer);
        }

        $quoteTransfer->getPayment()->getComputopDirectDebit()->setDirectDebitInitResponse(
            $responseTransfer
        );

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function saveInitResponse(QuoteTransfer $quoteTransfer)
    {
        $this->computopClient->saveDirectDebitInitResponse($quoteTransfer);
    }
}
