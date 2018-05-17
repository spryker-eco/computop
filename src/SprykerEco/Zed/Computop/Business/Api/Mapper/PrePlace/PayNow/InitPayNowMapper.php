<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Mapper\PrePlace\PayNow;

use Generated\Shared\Transfer\ComputopPayNowInitResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PrePlace\AbstractPrePlaceMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PrePlace\ApiPrePlaceMapperInterface;

class InitPayNowMapper extends AbstractPrePlaceMapper implements ApiPrePlaceMapperInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPaymentTransfer
     *
     * @return array
     */
    public function getDataSubArray(TransferInterface $computopPaymentTransfer)
    {
        /** @var \Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer $computopPaymentTransfer */
        $dataSubArray[ComputopApiConfig::PAY_ID] = $computopPaymentTransfer->getPayId();
        $dataSubArray[ComputopApiConfig::TRANS_ID] = $computopPaymentTransfer->getTransId();
        $dataSubArray[ComputopApiConfig::REQ_ID] = $computopPaymentTransfer->getReqId();
        $dataSubArray[ComputopApiConfig::REF_NR] = $computopPaymentTransfer->getRefNr();
        $dataSubArray[ComputopApiConfig::AMOUNT] = $computopPaymentTransfer->getAmount();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $computopPaymentTransfer->getCurrency();
        $dataSubArray[ComputopApiConfig::MAC] = $computopPaymentTransfer->getMac();
        $dataSubArray[ComputopApiConfig::EVENT_TOKEN] = ComputopApiConfig::EVENT_TOKEN_GET;

        return $dataSubArray;
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopPayNowInitResponseTransfer
     */
    protected function createPaymentTransfer()
    {
        return new ComputopPayNowInitResponseTransfer();
    }
}
