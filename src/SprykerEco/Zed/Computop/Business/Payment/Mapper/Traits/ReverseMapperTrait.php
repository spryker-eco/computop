<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Mapper\Traits;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConstants;

trait ReverseMapperTrait
{

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPaymentTransfer
     *
     * @return array
     */
    public function getDataSubArray(TransferInterface $computopPaymentTransfer)
    {
        $dataSubArray[ComputopConstants::PAY_ID_F_N] = $computopPaymentTransfer->getPayId();
        $dataSubArray[ComputopConstants::TRANS_ID_F_N] = $computopPaymentTransfer->getTransId();
        $dataSubArray[ComputopConstants::AMOUNT_F_N] = $computopPaymentTransfer->getAmount();
        $dataSubArray[ComputopConstants::CURRENCY_F_N] = $computopPaymentTransfer->getCurrency();
        $dataSubArray[ComputopConstants::MAC_F_N] = $computopPaymentTransfer->getMac();

        return $dataSubArray;
    }

}