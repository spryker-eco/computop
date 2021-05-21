<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\CheckoutPage\Process\Steps;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AbstractBaseStep;

class ComputopPayPalExpressCompleteStep extends AbstractBaseStep
{
    public function requireInput(AbstractTransfer $quoteTransfer)
    {
        return false;
    }

    public function postCondition(AbstractTransfer $quoteTransfer)
    {
        return true;
    }
}
