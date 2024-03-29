<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form\DataProvider;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class PayPalExpressFormDataProvider extends AbstractFormDataProvider
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getPayment() === null) {
            $quoteTransfer->setPayment(new PaymentTransfer());
        }

        if ($this->isValidPayment($quoteTransfer)) {
            return $quoteTransfer;
        }

        $paymentTransfer = $quoteTransfer->getPayment();
        /** @var \Generated\Shared\Transfer\ComputopPayPalExpressPaymentTransfer $computopPayPalExpressPaymentTransfer */
        $computopPayPalExpressPaymentTransfer = $this->mapper->createComputopPaymentTransfer($quoteTransfer);
        $paymentTransfer->setComputopPayPalExpress($computopPayPalExpressPaymentTransfer);
        $paymentTransfer->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        $quoteTransfer->setPayment($paymentTransfer);
        if ($quoteTransfer->getCustomer() === null) {
            $quoteTransfer->setCustomer(new CustomerTransfer());
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
        return $quoteTransfer->getPayment()->getComputopPayPalExpress();
    }
}
