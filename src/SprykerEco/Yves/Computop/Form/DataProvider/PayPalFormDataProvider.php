<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form\DataProvider;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class PayPalFormDataProvider extends AbstractFormDataProvider
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer)
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        if ($quoteTransfer->getPayment() === null) {
            $paymentTransfer = new PaymentTransfer();
            $quoteTransfer->setPayment($paymentTransfer);
        }

        if (!$this->isValidPayment($quoteTransfer)) {
            $paymentTransfer = $quoteTransfer->getPayment();
            /** @var \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer $computopTransfer */
            $computopTransfer = $this->mapper->createComputopPaymentTransfer($quoteTransfer);
            $paymentTransfer->setComputopPayPal($computopTransfer);
            $quoteTransfer->setPayment($paymentTransfer);
            $this->quoteClient->setQuote($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|null
     */
    protected function getComputopPayment(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getPayment()->getComputopPayPal();
    }
}
