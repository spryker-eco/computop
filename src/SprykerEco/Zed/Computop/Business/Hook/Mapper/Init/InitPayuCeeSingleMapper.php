<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Hook\Mapper\Init;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class InitPayuCeeSingleMapper extends AbstractMapper
{
    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return ComputopConfig::PAYMENT_METHOD_PAYU_CEE_SINGLE;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPaymentTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function updateComputopPaymentTransfer(
        QuoteTransfer $quoteTransfer,
        TransferInterface $computopPaymentTransfer
    ): TransferInterface {
        /** @var \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer $computopPaymentTransfer */
        $computopPaymentTransfer = parent::updateComputopPaymentTransfer($quoteTransfer, $computopPaymentTransfer);
        $computopPaymentTransfer->setMerchantId($this->config->getMerchantId());
        $computopPaymentTransfer->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        $computopPaymentTransfer->setMac(
            $this->computopApiService->generateEncryptedMac(
                $this->createRequestTransfer($computopPaymentTransfer)
            )
        );

        $decryptedValues = $this->computopApiService->getEncryptedArray(
            $this->getDataSubArray($computopPaymentTransfer),
            $this->config->getBlowfishPass()
        );

        if (isset($decryptedValues[ComputopApiConfig::LENGTH], $decryptedValues[ComputopApiConfig::DATA])) {
            $length = $decryptedValues[ComputopApiConfig::LENGTH];
            $data = $decryptedValues[ComputopApiConfig::DATA];

            $computopPaymentTransfer->setData($data);
            $computopPaymentTransfer->setLen($length);
            $computopPaymentTransfer->setUrl($this->getUrlToComputop($computopPaymentTransfer->getMerchantId(), $data, $length));
        }

        return $computopPaymentTransfer;
    }

    /**
     * @return string|null
     */
    protected function getActionUrl(): ?string
    {
        return $this->config->getPayuCeeSingleInitAction();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPayuCeeSinglePaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(TransferInterface $computopPayuCeeSinglePaymentTransfer): array
    {
        /** @var \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer $computopPayuCeeSinglePaymentTransfer */
        $dataSubArray[ComputopApiConfig::MERCHANT_ID] = $computopPayuCeeSinglePaymentTransfer->getMerchantId();
        $dataSubArray[ComputopApiConfig::AMOUNT] = $computopPayuCeeSinglePaymentTransfer->getAmount();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $computopPayuCeeSinglePaymentTransfer->getCurrency();
        $dataSubArray[ComputopApiConfig::RESPONSE] = $computopPayuCeeSinglePaymentTransfer->getResponse();
        $dataSubArray[ComputopApiConfig::IP_ADDRESS] = $computopPayuCeeSinglePaymentTransfer->getClientIp();
        $dataSubArray[ComputopApiConfig::REQ_ID] = $computopPayuCeeSinglePaymentTransfer->getReqId();
        $dataSubArray[ComputopApiConfig::TRANS_ID] = $computopPayuCeeSinglePaymentTransfer->getTransId();
        $dataSubArray[ComputopApiConfig::URL_FAILURE] = $computopPayuCeeSinglePaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopApiConfig::URL_NOTIFY] = $computopPayuCeeSinglePaymentTransfer->getUrlNotify();
        $dataSubArray[ComputopApiConfig::SHIPPING_ZIP] = $computopPayuCeeSinglePaymentTransfer->getShippingZip();

        $dataSubArray[ComputopApiConfig::URL_SUCCESS] = $computopPayuCeeSinglePaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopApiConfig::MAC] = $computopPayuCeeSinglePaymentTransfer->getMac();
        $dataSubArray[ComputopApiConfig::ORDER_DESC] = $computopPayuCeeSinglePaymentTransfer->getOrderDesc();
        $dataSubArray[ComputopApiConfig::ETI_ID] = $this->config->getEtiId();
        $dataSubArray[ComputopApiConfig::PAY_TYPE] = ComputopApiConfig::PAYU_CEE_DEFAULT_PAY_TYPE;

        return $dataSubArray;
    }
}
