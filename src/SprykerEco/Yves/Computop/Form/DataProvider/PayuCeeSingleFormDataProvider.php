<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form\DataProvider;

use Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class PayuCeeSingleFormDataProvider extends AbstractFormDataProvider
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer|null
     */
    protected function getComputopPayment(QuoteTransfer $quoteTransfer): ?ComputopPayuCeeSinglePaymentTransfer
    {
        return $quoteTransfer->getPayment()->getComputopPayuCeeSingle();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer): AbstractTransfer
    {
        if ($quoteTransfer->getPayment() === null) {
            $quoteTransfer->setPayment(new PaymentTransfer());
        }

        if ($this->isValidPayment($quoteTransfer)) {
            return $quoteTransfer;
        }

        return $this->setDefaultPayment($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setDefaultPayment(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer $computopTransfer */
        $computopTransfer = $this->mapper->createComputopPaymentTransfer($quoteTransfer);

        $paymentTransfer = $quoteTransfer->getPayment();
        $paymentTransfer->setComputopPayuCeeSingle($computopTransfer);

        $quoteTransfer->setPayment($paymentTransfer);
        $this->quoteClient->setQuote($quoteTransfer);

        return $quoteTransfer;
    }
}
