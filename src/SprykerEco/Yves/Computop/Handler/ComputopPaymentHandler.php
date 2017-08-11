<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler;

use Generated\Shared\Transfer\ComputopCreditCardOrderResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

class ComputopPaymentHandler
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ComputopCreditCardOrderResponseTransfer $creditCardOrderResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addPaymentToQuote(QuoteTransfer $quoteTransfer, ComputopCreditCardOrderResponseTransfer $creditCardOrderResponseTransfer)
    {
        if ($quoteTransfer->getPayment() !== null) {
            $quoteTransfer->getPayment()->getComputopCreditCard()->setCreditCardOrderResponse(
                $creditCardOrderResponseTransfer
            );

            $this->setPaymentProviderMethodSelection($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function setPaymentProviderMethodSelection(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer
            ->getPayment()
            ->setPaymentProvider(ComputopConstants::PROVIDER_NAME)
            ->setPaymentMethod(ComputopConstants::PAYMENT_METHOD_CREDIT_CARD)
            ->setPaymentSelection(ComputopConstants::PAYMENT_METHOD_CREDIT_CARD);
    }

}
