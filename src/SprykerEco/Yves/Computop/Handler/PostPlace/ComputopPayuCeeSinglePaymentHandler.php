<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\PostPlace;

use Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class ComputopPayuCeeSinglePaymentHandler extends AbstractPostPlacePaymentHandler
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addPaymentToQuote(QuoteTransfer $quoteTransfer, AbstractTransfer $responseTransfer): QuoteTransfer
    {
        $paymentTransfer = $quoteTransfer->getPayment();
        if (!$paymentTransfer) {
            $paymentTransfer = new PaymentTransfer();
        }

        if (!$paymentTransfer->getComputopPayuCeeSingle()) {
            $paymentTransfer->setComputopPayuCeeSingle(new ComputopPayuCeeSinglePaymentTransfer());
        }

        $paymentTransfer->getComputopPayuCeeSingle()->setPayuCeeSingleInitResponse($responseTransfer);
        $quoteTransfer->setPayment($paymentTransfer);

        $quoteTransfer->setPayment($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function saveInitResponse(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->computopClient->savePayuCeeSingleInitResponse($quoteTransfer);
    }
}
