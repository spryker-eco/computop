<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Mapper\Traits;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopFieldNameConstants;

trait InquireMapperTrait
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPaymentTransfer
     *
     * @return array
     */
    public function getDataSubArray(TransferInterface $computopPaymentTransfer)
    {
        $dataSubArray[ComputopFieldNameConstants::PAY_ID] = $computopPaymentTransfer->getPayId();
        $dataSubArray[ComputopFieldNameConstants::TRANS_ID] = $computopPaymentTransfer->getTransId();
        $dataSubArray[ComputopFieldNameConstants::MAC] = $computopPaymentTransfer->getMac();

        return $dataSubArray;
    }
}
