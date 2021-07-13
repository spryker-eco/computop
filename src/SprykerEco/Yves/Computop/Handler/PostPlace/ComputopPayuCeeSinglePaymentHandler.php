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
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addPaymentToQuote(QuoteTransfer $quoteTransfer, AbstractTransfer $responseTransfer): QuoteTransfer
    {
        $payment = $quoteTransfer->getPayment();

        if (!$payment) {
            $payment = new PaymentTransfer();
        }

        if ($payment->getComputopPayuCeeSingle() === null) {
            $payment->setComputopPayuCeeSingle(new ComputopPayuCeeSinglePaymentTransfer());
        }

        $payment->getComputopPayuCeeSingle()->setPayuCeeSingleInitResponse($responseTransfer);

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
