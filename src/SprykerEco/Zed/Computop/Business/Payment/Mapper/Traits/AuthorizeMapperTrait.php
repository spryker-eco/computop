<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Mapper\Traits;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopFieldNameConstants;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceInterface;

trait AuthorizeMapperTrait
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
        $dataSubArray[ComputopFieldNameConstants::AMOUNT] = $computopPaymentTransfer->getAmount();
        $dataSubArray[ComputopFieldNameConstants::CURRENCY] = $computopPaymentTransfer->getCurrency();
        $dataSubArray[ComputopFieldNameConstants::CAPTURE] = $computopPaymentTransfer->getCapture();
        $dataSubArray[ComputopFieldNameConstants::MAC] = $computopPaymentTransfer->getMac();
        $dataSubArray[ComputopFieldNameConstants::ORDER_DESC] = $computopPaymentTransfer->getOrderDesc();

        return $dataSubArray;
    }

    /**
     * @param \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceInterface $computopService
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    protected function getOrderDesc(ComputopToComputopServiceInterface $computopService, OrderTransfer $orderTransfer)
    {
        return $computopService->getTestModeDescriptionValue(
            $orderTransfer->getItems()->getArrayCopy()
        );
    }

}
