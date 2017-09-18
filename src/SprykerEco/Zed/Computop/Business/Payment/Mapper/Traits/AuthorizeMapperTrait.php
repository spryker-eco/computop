<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Mapper\Traits;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConstants;
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
        $dataSubArray[ComputopConstants::PAY_ID_F_N] = $computopPaymentTransfer->getPayId();
        $dataSubArray[ComputopConstants::TRANS_ID_F_N] = $computopPaymentTransfer->getTransId();
        $dataSubArray[ComputopConstants::AMOUNT_F_N] = $computopPaymentTransfer->getAmount();
        $dataSubArray[ComputopConstants::CURRENCY_F_N] = $computopPaymentTransfer->getCurrency();
        $dataSubArray[ComputopConstants::CAPTURE_F_N] = $computopPaymentTransfer->getCapture();
        $dataSubArray[ComputopConstants::MAC_F_N] = $computopPaymentTransfer->getMac();
        $dataSubArray[ComputopConstants::ORDER_DESC_F_N] = $computopPaymentTransfer->getOrderDesc();

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
