<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
        if (
            !$quoteTransfer->getPayment()
            || $quoteTransfer->getPayment()->getPaymentSelection() !== ComputopConfig::PAYMENT_METHOD_CREDIT_CARD
        ) {
            return false;
        }

        return true;
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
        return [
            'formAction' => $quoteTransfer->getPayment()->getComputopCreditCard()->getUrl(),
            'encryptedData' => $quoteTransfer->getPayment()->getComputopCreditCard()->getData(),
            'encryptedDataLength' => $quoteTransfer->getPayment()->getComputopCreditCard()->getLen(),
            'merchantId' => $quoteTransfer->getPayment()->getComputopCreditCard()->getMerchantId(),
            'urlBack' => $quoteTransfer->getPayment()->getComputopCreditCard()->getUrlBack(),
        ];
    }
}
