<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;

interface HashInterface
{

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getMacValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer);

}
