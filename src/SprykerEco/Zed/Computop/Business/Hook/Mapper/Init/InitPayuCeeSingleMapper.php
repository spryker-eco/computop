<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Hook\Mapper\Init;

use Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer;
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
            $urlToComputop = $this->getUrlToComputop($this->getActionUrl(), $computopPaymentTransfer->getMerchantId(), $data, $length);
            $computopPaymentTransfer->setUrl($urlToComputop);
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
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer $computopPayuCeeSinglePaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(ComputopPayuCeeSinglePaymentTransfer $computopPayuCeeSinglePaymentTransfer): array
    {
        return [
            ComputopApiConfig::MERCHANT_ID => $computopPayuCeeSinglePaymentTransfer->getMerchantId(),
            ComputopApiConfig::TRANS_ID => $computopPayuCeeSinglePaymentTransfer->getTransId(),
            ComputopApiConfig::REF_NR => $computopPayuCeeSinglePaymentTransfer->getRefNr(),
            ComputopApiConfig::AMOUNT => $computopPayuCeeSinglePaymentTransfer->getAmount(),
            ComputopApiConfig::CURRENCY => $computopPayuCeeSinglePaymentTransfer->getCurrency(),
            ComputopApiConfig::MAC => $computopPayuCeeSinglePaymentTransfer->getMac(),
            ComputopApiConfig::URL_SUCCESS => $computopPayuCeeSinglePaymentTransfer->getUrlSuccess(),
            ComputopApiConfig::URL_FAILURE => $computopPayuCeeSinglePaymentTransfer->getUrlFailure(),
            ComputopApiConfig::RESPONSE => $computopPayuCeeSinglePaymentTransfer->getResponse(),
            ComputopApiConfig::URL_NOTIFY => $computopPayuCeeSinglePaymentTransfer->getUrlNotify(),
            ComputopApiConfig::REQ_ID => $computopPayuCeeSinglePaymentTransfer->getReqId(),
            ComputopApiConfig::CAPTURE => $computopPayuCeeSinglePaymentTransfer->getCapture(),
            ComputopApiConfig::ORDER_DESC => $computopPayuCeeSinglePaymentTransfer->getOrderDesc(),
            ComputopApiConfig::ARTICLE_LIST => $computopPayuCeeSinglePaymentTransfer->getArticleList(),
            ComputopApiConfig::FIRST_NAME => $computopPayuCeeSinglePaymentTransfer->getFirstName(),
            ComputopApiConfig::LAST_NAME => $computopPayuCeeSinglePaymentTransfer->getLastName(),
            ComputopApiConfig::EMAIL_ADDRESS => $computopPayuCeeSinglePaymentTransfer->getEmail(),
            ComputopApiConfig::LANGUAGE => strtolower($computopPayuCeeSinglePaymentTransfer->getLanguage()),
        ];
    }
}
