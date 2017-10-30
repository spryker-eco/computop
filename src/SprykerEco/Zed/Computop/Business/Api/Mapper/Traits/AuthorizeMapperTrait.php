<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Mapper\Traits;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\Config\ComputopFieldName;
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
        $dataSubArray[ComputopFieldName::PAY_ID] = $computopPaymentTransfer->getPayId();
        $dataSubArray[ComputopFieldName::TRANS_ID] = $computopPaymentTransfer->getTransId();
        $dataSubArray[ComputopFieldName::AMOUNT] = $computopPaymentTransfer->getAmount();
        $dataSubArray[ComputopFieldName::CURRENCY] = $computopPaymentTransfer->getCurrency();
        $dataSubArray[ComputopFieldName::CAPTURE] = $computopPaymentTransfer->getCapture();
        $dataSubArray[ComputopFieldName::MAC] = $computopPaymentTransfer->getMac();
        $dataSubArray[ComputopFieldName::ORDER_DESC] = $computopPaymentTransfer->getOrderDesc();

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
