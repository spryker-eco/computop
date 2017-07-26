<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;

class Computop implements ComputopInterface
{

    const SEPARATOR = '*';

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getMacValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        $macDataArray = [
            $cardPaymentTransfer->getPayId(),
            $cardPaymentTransfer->getTransId(),
            $cardPaymentTransfer->getMerchantId(),
            $cardPaymentTransfer->getAmount(),
            $cardPaymentTransfer->getCurrency(),
        ];

        return implode(self::SEPARATOR, $macDataArray);
    }

}
