<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form\DataProvider;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class SofortFormDataProvider extends AbstractFormDataProvider
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getPayment() === null) {
            $paymentTransfer = new PaymentTransfer();
            $quoteTransfer->setPayment($paymentTransfer);
        }

        if (!$this->isValidPayment($quoteTransfer)) {
            $paymentTransfer = $quoteTransfer->getPaymentOrFail();
            /** @var \Generated\Shared\Transfer\ComputopSofortPaymentTransfer $computopTransfer */
            $computopTransfer = $this->mapper->createComputopPaymentTransfer($quoteTransfer);
            $paymentTransfer->setComputopSofort($computopTransfer);
            $quoteTransfer->setPayment($paymentTransfer);
            $this->quoteClient->setQuote($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function getComputopPayment(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getPaymentOrFail()->getComputopSofort();
    }
}
