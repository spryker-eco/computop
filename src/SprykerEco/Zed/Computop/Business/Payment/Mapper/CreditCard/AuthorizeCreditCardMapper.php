<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

class AuthorizeCreditCardMapper extends AbstractCreditCardMapper implements CreditCardMapperInterface
{

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return array
     */
    public function getDataSubArray(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        $dataSubArray[ComputopConstants::PAY_ID_F_N] = $cardPaymentTransfer->getPayId();
        $dataSubArray[ComputopConstants::TRANS_ID_F_N] = $cardPaymentTransfer->getTransId();
        $dataSubArray[ComputopConstants::AMOUNT_F_N] = $cardPaymentTransfer->getAmount();
        $dataSubArray[ComputopConstants::CURRENCY_F_N] = $cardPaymentTransfer->getCurrency();
        $dataSubArray[ComputopConstants::CAPTURE_F_N] = $cardPaymentTransfer->getCapture();
        $dataSubArray[ComputopConstants::MAC_F_N] = $cardPaymentTransfer->getMac();
        $dataSubArray[ComputopConstants::ORDER_DESC_F_N] = $cardPaymentTransfer->getOrderDesc();

        return $dataSubArray;
    }

}
