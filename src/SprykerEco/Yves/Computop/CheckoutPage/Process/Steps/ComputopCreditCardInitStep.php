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
        return $quoteTransfer->getPaymentOrFail()->getPaymentSelection() !== ComputopConfig::PAYMENT_METHOD_CREDIT_CARD;
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
        $computopCreditCard = $quoteTransfer->getPaymentOrFail()->getComputopCreditCardOrFail();

        return [
            'formAction' => $computopCreditCard->getUrl(),
            'encryptedData' => $computopCreditCard->getData(),
            'encryptedDataLength' => $computopCreditCard->getLen(),
            'merchantId' => $computopCreditCard->getMerchantId(),
            'urlBack' => $computopCreditCard->getUrlBack(),
        ];
    }
}
