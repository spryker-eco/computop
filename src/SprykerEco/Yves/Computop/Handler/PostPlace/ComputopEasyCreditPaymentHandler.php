<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\PostPlace;

use Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class ComputopEasyCreditPaymentHandler extends AbstractPostPlacePaymentHandler
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $responseArray
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handle(QuoteTransfer $quoteTransfer, array $responseArray)
    {
        $quoteTransfer = parent::handle($quoteTransfer, $responseArray);
        $quoteTransfer->getPayment()->getComputopEasyCredit()->fromArray(
            $quoteTransfer->getPayment()->getComputopEasyCredit()->getEasyCreditInitResponse()->getHeader()->toArray(),
            true
        );

        $quoteTransfer = $this->computopClient->easyCreditStatusApiCall($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addPaymentToQuote(QuoteTransfer $quoteTransfer, AbstractTransfer $responseTransfer)
    {
        if ($quoteTransfer->getPayment() === null) {
            $paymentTransfer = new PaymentTransfer();
            $quoteTransfer->setPayment($paymentTransfer);
        }

        if ($quoteTransfer->getPayment()->getComputopEasyCredit() === null) {
            $computopTransfer = new ComputopEasyCreditPaymentTransfer();
            $quoteTransfer->getPayment()->setComputopEasyCredit($computopTransfer);
        }

        $quoteTransfer->getPayment()->getComputopEasyCredit()->setEasyCreditInitResponse(
            $responseTransfer
        );

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function saveInitResponse(QuoteTransfer $quoteTransfer)
    {
        $this->computopClient->saveEasyCreditInitResponse($quoteTransfer);
    }
}
