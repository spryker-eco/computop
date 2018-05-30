<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Mapper\Init\PayNow;

use Generated\Shared\Transfer\ComputopPayNowInitResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Zed\Computop\Business\Api\Mapper\Init\AbstractInitMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\Init\ApiInitMapperInterface;
use SprykerEco\Zed\Computop\ComputopConfig;

class InitPayNowMapper extends AbstractInitMapper implements ApiInitMapperInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPaymentTransfer
     *
     * @return array
     */
    public function getDataSubArray(TransferInterface $computopPaymentTransfer)
    {
        /** @var \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $computopPaymentTransfer */

        $dataSubArray[ComputopApiConfig::TRANS_ID] = $computopPaymentTransfer->getTransId();
        $dataSubArray[ComputopApiConfig::AMOUNT] = $computopPaymentTransfer->getAmount();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $computopPaymentTransfer->getCurrency();
        $dataSubArray[ComputopApiConfig::URL_SUCCESS] = $computopPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopApiConfig::URL_FAILURE] = $computopPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopApiConfig::URL_NOTIFY] = $computopPaymentTransfer->getUrlNotify();
        $dataSubArray[ComputopApiConfig::CAPTURE] = $computopPaymentTransfer->getCapture();
        $dataSubArray[ComputopApiConfig::RESPONSE] = $computopPaymentTransfer->getResponse();
        $dataSubArray[ComputopApiConfig::MAC] = $computopPaymentTransfer->getMac();
        $dataSubArray[ComputopApiConfig::TX_TYPE] = $computopPaymentTransfer->getTxType();
        $dataSubArray[ComputopApiConfig::ORDER_DESC] = $computopPaymentTransfer->getOrderDesc();
        $dataSubArray[ComputopApiConfig::ETI_ID] = ComputopConfig::ETI_ID;
        $dataSubArray[ComputopApiConfig::REF_NR] = $computopPaymentTransfer->getRefNr();
        $dataSubArray[ComputopApiConfig::REQ_ID] = $computopPaymentTransfer->getReqId();

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
