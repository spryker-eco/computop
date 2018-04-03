<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Mapper\Traits;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Service\Computop\ComputopServiceInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

trait AuthorizeMapperTrait
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPaymentTransfer
     *
     * @return array
     */
    public function getDataSubArray(TransferInterface $computopPaymentTransfer)
    {
        $dataSubArray[ComputopApiConfig::PAY_ID] = $computopPaymentTransfer->getPayId();
        $dataSubArray[ComputopApiConfig::TRANS_ID] = $computopPaymentTransfer->getTransId();
        $dataSubArray[ComputopApiConfig::REQ_ID] = $computopPaymentTransfer->getReqId();
        $dataSubArray[ComputopApiConfig::REF_NR] = $computopPaymentTransfer->getRefNr();
        $dataSubArray[ComputopApiConfig::AMOUNT] = $computopPaymentTransfer->getAmount();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $computopPaymentTransfer->getCurrency();
        $dataSubArray[ComputopApiConfig::CAPTURE] = $computopPaymentTransfer->getCapture();
        $dataSubArray[ComputopApiConfig::MAC] = $computopPaymentTransfer->getMac();
        $dataSubArray[ComputopApiConfig::ORDER_DESC] = $computopPaymentTransfer->getOrderDesc();

        return $dataSubArray;
    }

    /**
     * @param \SprykerEco\Service\Computop\ComputopServiceInterface $computopService
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    protected function getOrderDesc(ComputopServiceInterface $computopService, OrderTransfer $orderTransfer)
    {
        return $computopService->getDescriptionValue(
            $orderTransfer->getItems()->getArrayCopy()
        );
    }
}
