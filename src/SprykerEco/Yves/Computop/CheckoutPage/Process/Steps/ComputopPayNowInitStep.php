<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\CheckoutPage\Process\Steps;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AbstractBaseStep;

class ComputopPayNowInitStep extends AbstractBaseStep
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getPaymentOrFail()->getPaymentSelection() !== ComputopConfig::PAYMENT_METHOD_PAY_NOW;
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
        $computopPayNow = $quoteTransfer->getPaymentOrFail()->getComputopPayNowOrFail();

        return [
            'formAction' => $computopPayNow->getUrl(),
            'encryptedData' => $computopPayNow->getData(),
            'encryptedDataLength' => $computopPayNow->getLen(),
            'merchantId' => $computopPayNow->getMerchantId(),
            'brandOptions' => $this->getBrandOptions(),
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    protected function getBrandOptions(): array
    {
        return [
            'VISA' => [
                'value' => 'VISA',
                'label' => 'Visa',
            ],
            'MasterCard' => [
                'value' => 'MasterCard',
                'label' => 'Master Card',
            ],
            'AMEX' => [
                'value' => 'AMEX',
                'label' => 'American Express',
            ],
            'DINERS' => [
                'value' => 'DINERS',
                'label' => 'Diners Club',
            ],
            'JCB' => [
                'value' => 'JCB',
                'label' => 'JCB',
            ],
            'CBN' => [
                'value' => 'CBN',
                'label' => 'CBN',
            ],
            'SWITCH' => [
                'value' => 'SWITCH',
                'label' => 'Switch',
            ],
            'SOLO' => [
                'value' => 'SOLO',
                'label' => 'Solo',
            ],
        ];
    }
}
