<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\CheckoutPage\Process\Steps;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PaymentStep as SprykerShopPaymentStep;

class PaymentStep extends SprykerShopPaymentStep
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemHidden(AbstractTransfer $quoteTransfer): bool
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        return $quoteTransfer->getPayment() !== null
            && $quoteTransfer->getPaymentOrFail()->getComputopPayPalExpress() !== null;
    }
}
