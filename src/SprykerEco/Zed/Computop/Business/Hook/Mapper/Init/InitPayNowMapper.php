<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Hook\Mapper\Init;

use Generated\Shared\Transfer\ComputopPayNowPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Zed\Computop\ComputopConfig as ZedComputopConfig;

class InitPayNowMapper extends AbstractMapper
{
    /**
     * @return string
     */
    public function getMethodName()
    {
        return ComputopConfig::PAYMENT_METHOD_PAY_NOW;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $computopPaymentTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function updateComputopPaymentTransfer(QuoteTransfer $quoteTransfer, TransferInterface $computopPaymentTransfer)
    {
        $computopPaymentTransfer->setRefNr($quoteTransfer->getOrderReference());
        $decryptedValues = $this->computopApiService->getEncryptedArray(
            $this->getDataSubArray($computopPaymentTransfer),
            $this->config->getBlowfishPass()
        );

        $computopPaymentTransfer->setData($decryptedValues[ComputopApiConfig::DATA]);
        $computopPaymentTransfer->setLen($decryptedValues[ComputopApiConfig::LENGTH]);

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $cardPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(ComputopPayNowPaymentTransfer $cardPaymentTransfer)
    {
        $dataSubArray[ComputopApiConfig::TRANS_ID] = $cardPaymentTransfer->getTransId();
        $dataSubArray[ComputopApiConfig::AMOUNT] = $cardPaymentTransfer->getAmount();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $cardPaymentTransfer->getCurrency();
        $dataSubArray[ComputopApiConfig::URL_SUCCESS] = $cardPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopApiConfig::URL_FAILURE] = $cardPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopApiConfig::CAPTURE] = $cardPaymentTransfer->getCapture();
        $dataSubArray[ComputopApiConfig::RESPONSE] = $cardPaymentTransfer->getResponse();
        $dataSubArray[ComputopApiConfig::MAC] = $cardPaymentTransfer->getMac();
        $dataSubArray[ComputopApiConfig::TX_TYPE] = $cardPaymentTransfer->getTxType();
        $dataSubArray[ComputopApiConfig::ORDER_DESC] = $cardPaymentTransfer->getOrderDesc();
        $dataSubArray[ComputopApiConfig::REF_NR] = $cardPaymentTransfer->getRefNr();
        $dataSubArray[ComputopApiConfig::ETI_ID] = $this->config->getEtiId();

        return $dataSubArray;
    }
}
