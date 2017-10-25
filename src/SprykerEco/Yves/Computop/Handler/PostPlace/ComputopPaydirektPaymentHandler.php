<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\PostPlace;

use Generated\Shared\Transfer\ComputopPaydirektPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class ComputopPaydirektPaymentHandler implements ComputopPaymentHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addPaymentToQuote(QuoteTransfer $quoteTransfer, AbstractTransfer $responseTransfer)
    {
        if ($quoteTransfer->getPayment() === null) {
            $paymentTransfer = new PaymentTransfer();
            $quoteTransfer->setPayment($paymentTransfer);
        }

        if ($quoteTransfer->getPayment()->getComputopPaydirekt() === null) {
            $computopTransfer = new ComputopPaydirektPaymentTransfer();
            $quoteTransfer->getPayment()->setComputopPaydirekt($computopTransfer);
        }

        $quoteTransfer->getPayment()->getComputopPaydirekt()->setPaydirektOrderResponse(
            $responseTransfer
        );

        return $quoteTransfer;
    }
}