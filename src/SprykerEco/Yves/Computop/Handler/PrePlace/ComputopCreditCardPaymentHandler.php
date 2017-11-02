<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\PrePlace;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;

class ComputopCreditCardPaymentHandler implements ComputopPaymentHandlerInterface
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
            $quoteTransfer->getPayment()->getComputopCreditCard()->setCreditCardInitResponse(
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
            ->setPaymentProvider(ComputopConfig::PROVIDER_NAME)
            ->setPaymentMethod(ComputopConfig::PAYMENT_METHOD_CREDIT_CARD)
            ->setPaymentSelection(ComputopConfig::PAYMENT_METHOD_CREDIT_CARD);

        return $quoteTransfer;
    }
}
