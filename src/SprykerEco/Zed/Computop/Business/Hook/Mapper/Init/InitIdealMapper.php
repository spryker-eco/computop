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

class InitIdealMapper extends AbstractMapper
{
    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return ComputopConfig::PAYMENT_METHOD_IDEAL;
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
        /** @var \Generated\Shared\Transfer\ComputopIdealPaymentTransfer $computopPaymentTransfer */
        $computopPaymentTransfer = parent::updateComputopPaymentTransfer($quoteTransfer, $computopPaymentTransfer);
        $computopPaymentTransfer->setMerchantId($this->config->getMerchantId());
        $computopPaymentTransfer->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        $computopPaymentTransfer->setMac(
            $this->computopApiService->generateEncryptedMac(
                $this->createRequestTransfer($computopPaymentTransfer),
            ),
        );

        $decryptedValues = $this->computopApiService->getEncryptedArray(
            $this->getDataSubArray($computopPaymentTransfer),
            $this->config->getBlowfishPass(),
        );

        $length = $decryptedValues[ComputopApiConfig::LENGTH];
        $data = $decryptedValues[ComputopApiConfig::DATA];

        $computopPaymentTransfer->setData($data);
        $computopPaymentTransfer->setLen($length);
        $computopPaymentTransfer->setUrl($this->getUrlToComputop($computopPaymentTransfer->getMerchantId(), $data, $length));

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopIdealPaymentTransfer $computopIdealPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(TransferInterface $computopIdealPaymentTransfer): array
    {
        /** @var \Generated\Shared\Transfer\ComputopIdealPaymentTransfer $computopIdealPaymentTransfer */
        return [
            ComputopApiConfig::TRANS_ID => $computopIdealPaymentTransfer->getTransId(),
            ComputopApiConfig::AMOUNT => $computopIdealPaymentTransfer->getAmount(),
            ComputopApiConfig::CURRENCY => $computopIdealPaymentTransfer->getCurrency(),
            ComputopApiConfig::URL_SUCCESS => $computopIdealPaymentTransfer->getUrlSuccess(),
            ComputopApiConfig::URL_NOTIFY => $computopIdealPaymentTransfer->getUrlNotify(),
            ComputopApiConfig::URL_FAILURE => $computopIdealPaymentTransfer->getUrlFailure(),
            ComputopApiConfig::RESPONSE => $computopIdealPaymentTransfer->getResponse(),
            ComputopApiConfig::MAC => $computopIdealPaymentTransfer->getMac(),
            ComputopApiConfig::ORDER_DESC => $computopIdealPaymentTransfer->getOrderDesc(),
            ComputopApiConfig::ETI_ID => $this->config->getEtiId(),
            ComputopApiConfig::IP_ADDRESS => $computopIdealPaymentTransfer->getClientIp(),
            ComputopApiConfig::SHIPPING_ZIP => $computopIdealPaymentTransfer->getShippingZip(),
            ComputopApiConfig::ISSUER_ID => $this->config->getIdealIssuerId(),
        ];
    }

    /**
     * @return string
     */
    protected function getActionUrl(): string
    {
        return $this->config->getIdealInitAction();
    }
}
