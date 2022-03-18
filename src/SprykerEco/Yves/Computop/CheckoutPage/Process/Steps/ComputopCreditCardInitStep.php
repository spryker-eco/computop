<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\CheckoutPage\Process\Steps;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AbstractBaseStep;

class ComputopCreditCardInitStep extends AbstractBaseStep
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $quoteTransfer): bool
    {
        $paymentTransfer = $quoteTransfer->getPayment();

        return $paymentTransfer && $paymentTransfer->getPaymentSelection() === ComputopConfig::PAYMENT_METHOD_CREDIT_CARD;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $quoteTransfer): bool
    {
        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getTemplateVariables(AbstractTransfer $quoteTransfer): array
    {
        $computopCreditCardPaymentTransfer = $quoteTransfer->getPaymentOrFail()->getComputopCreditCardOrFail();

        return [
            'formAction' => $computopCreditCardPaymentTransfer->getUrl(),
            'encryptedData' => $computopCreditCardPaymentTransfer->getData(),
            'encryptedDataLength' => $computopCreditCardPaymentTransfer->getLen(),
            'merchantId' => $computopCreditCardPaymentTransfer->getMerchantId(),
            'urlBack' => $computopCreditCardPaymentTransfer->getUrlBack(),
        ];
    }
}
