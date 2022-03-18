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

class InitSofortMapper extends AbstractMapper
{
    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return ComputopConfig::PAYMENT_METHOD_SOFORT;
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
        /** @var \Generated\Shared\Transfer\ComputopSofortPaymentTransfer $computopPaymentTransfer */
        $computopPaymentTransfer = parent::updateComputopPaymentTransfer($quoteTransfer, $computopPaymentTransfer);
        $computopPaymentTransfer->setMerchantId($this->config->getMerchantId());
        $computopPaymentTransfer->setAmount($quoteTransfer->getTotalsOrFail()->getGrandTotal());
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
        $url = $this->getUrlToComputop(
            $computopPaymentTransfer->getMerchantIdOrFail(),
            $data,
            $length,
        );

        return $computopPaymentTransfer->setData($data)
            ->setLen($length)
            ->setUrl($url);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopSofortPaymentTransfer $computopSofortPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(TransferInterface $computopSofortPaymentTransfer): array
    {
        return [
            ComputopApiConfig::TRANS_ID => $computopSofortPaymentTransfer->getTransId(),
            ComputopApiConfig::AMOUNT => $computopSofortPaymentTransfer->getAmount(),
            ComputopApiConfig::CURRENCY => $computopSofortPaymentTransfer->getCurrency(),
            ComputopApiConfig::URL_SUCCESS => $computopSofortPaymentTransfer->getUrlSuccess(),
            ComputopApiConfig::URL_NOTIFY => $computopSofortPaymentTransfer->getUrlNotify(),
            ComputopApiConfig::URL_FAILURE => $computopSofortPaymentTransfer->getUrlFailure(),
            ComputopApiConfig::RESPONSE => $computopSofortPaymentTransfer->getResponse(),
            ComputopApiConfig::MAC => $computopSofortPaymentTransfer->getMac(),
            ComputopApiConfig::ORDER_DESC => $computopSofortPaymentTransfer->getOrderDesc(),
            ComputopApiConfig::ETI_ID => $this->config->getEtiId(),
            ComputopApiConfig::IP_ADDRESS => $computopSofortPaymentTransfer->getClientIp(),
            ComputopApiConfig::SHIPPING_ZIP => $computopSofortPaymentTransfer->getShippingZip(),
        ];
    }

    /**
     * @return string
     */
    protected function getActionUrl(): string
    {
        return $this->config->getSofortInitAction();
    }
}
