<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\PrePlace;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;

class ComputopPayPalPaymentHandler extends AbstractPrePlacePaymentHandler
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addPaymentToQuote(QuoteTransfer $quoteTransfer, AbstractTransfer $responseTransfer)
    {
        if ($quoteTransfer->getPayment() !== null) {
            $quoteTransfer->getPayment()->getComputopPayPal()->setPayPalInitResponse(
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
            ->setPaymentProvider(ComputopSharedConfig::PROVIDER_NAME)
            ->setPaymentMethod(ComputopSharedConfig::PAYMENT_METHOD_PAY_PAL)
            ->setPaymentSelection(ComputopSharedConfig::PAYMENT_METHOD_PAY_PAL);

        return $quoteTransfer;
    }
}
