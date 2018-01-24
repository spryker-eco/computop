<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\PrePlace;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;

class ComputopEasyCreditPaymentHandler extends AbstractPrePlacePaymentHandler
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
        //$this->computopClient->logResponse($quoteTransfer->getPayment()->getComputopEasyCredit()->getEasyCreditStatusResponse()->getHeader());

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
        if ($quoteTransfer->getPayment() !== null) {
            $quoteTransfer->getPayment()->getComputopEasyCredit()->setEasyCreditInitResponse(
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
            ->setPaymentMethod(ComputopConfig::PAYMENT_METHOD_EASY_CREDIT)
            ->setPaymentSelection(ComputopConfig::PAYMENT_METHOD_EASY_CREDIT);

        return $quoteTransfer;
    }
}
