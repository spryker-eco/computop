<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

class ComputopDirectDebitPaymentHandler implements ComputopPaymentHandlerInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addPaymentToQuote(QuoteTransfer $quoteTransfer, AbstractTransfer $responseTransfer)
    {
        if ($quoteTransfer->getPayment() !== null) {
            $quoteTransfer->getPayment()->getComputopDirectDebit()->setDirectDebitOrderResponse(
                $responseTransfer
            );

            $quoteTransfer = $this->setPaymentProviderMethodSelection($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setPaymentProviderMethodSelection(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer
            ->getPayment()
            ->setPaymentProvider(ComputopConstants::PROVIDER_NAME)
            ->setPaymentMethod(ComputopConstants::PAYMENT_METHOD_DIRECT_DEBIT)
            ->setPaymentSelection(ComputopConstants::PAYMENT_METHOD_DIRECT_DEBIT);

        return $quoteTransfer;
    }

}