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
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPayuCeeSinglePaymentTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function updateComputopPaymentTransfer(
        QuoteTransfer $quoteTransfer,
        TransferInterface $computopPayuCeeSinglePaymentTransfer
    ): TransferInterface {
        /** @var \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer $computopPayuCeeSinglePaymentTransfer */
        $computopPayuCeeSinglePaymentTransfer = parent::updateComputopPaymentTransfer(
            $quoteTransfer,
            $computopPayuCeeSinglePaymentTransfer,
        );
        $computopPayuCeeSinglePaymentTransfer
            ->setRefNr($quoteTransfer->getOrderReference() . '-' . date('Y-m-d H:i:s'))
            ->setMerchantId($this->config->getMerchantId())
            ->setAmount($quoteTransfer->getTotals()->getGrandTotal());

        $requestTransfer = $this->createRequestTransfer($computopPayuCeeSinglePaymentTransfer);
        $encryptedMac = $this->computopApiService->generateEncryptedMac($requestTransfer);
        $computopPayuCeeSinglePaymentTransfer->setMac($encryptedMac);

        $encryptedValues = $this->computopApiService->getEncryptedArray(
            $this->getDataSubArray($computopPayuCeeSinglePaymentTransfer),
            $this->config->getBlowfishPass(),
        );

        if (!isset($encryptedValues[ComputopApiConfig::LENGTH], $encryptedValues[ComputopApiConfig::DATA])) {
            return $computopPayuCeeSinglePaymentTransfer;
        }

        $urlToComputop = $this->getUrlToComputop(
            (string)$computopPayuCeeSinglePaymentTransfer->getMerchantId(),
            $encryptedValues[ComputopApiConfig::DATA],
            $encryptedValues[ComputopApiConfig::LENGTH],
        );

        $computopPayuCeeSinglePaymentTransfer
            ->setData($encryptedValues[ComputopApiConfig::DATA])
            ->setLen($encryptedValues[ComputopApiConfig::LENGTH])
            ->setUrl($urlToComputop);

        return $computopPayuCeeSinglePaymentTransfer;
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
            ComputopApiConfig::LANGUAGE => strtolower((string)$computopPayuCeeSinglePaymentTransfer->getLanguage()),
        ];
    }
}
