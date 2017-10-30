<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Mapper\Traits;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\Config\ComputopFieldName;

trait ReverseMapperTrait
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPaymentTransfer
     *
     * @return array
     */
    public function getDataSubArray(TransferInterface $computopPaymentTransfer)
    {
        $dataSubArray[ComputopFieldName::PAY_ID] = $computopPaymentTransfer->getPayId();
        $dataSubArray[ComputopFieldName::TRANS_ID] = $computopPaymentTransfer->getTransId();
        $dataSubArray[ComputopFieldName::AMOUNT] = $computopPaymentTransfer->getAmount();
        $dataSubArray[ComputopFieldName::CURRENCY] = $computopPaymentTransfer->getCurrency();
        $dataSubArray[ComputopFieldName::MAC] = $computopPaymentTransfer->getMac();

        return $dataSubArray;
    }
}
