<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\CreditCard;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

interface CreditCardMapperInterface
{

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    public function createComputopCreditCardPaymentTransfer(AbstractTransfer $quoteTransfer);

}
