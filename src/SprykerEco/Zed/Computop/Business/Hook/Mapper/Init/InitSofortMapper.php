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

        $length = $decryptedValues[ComputopApiConfig::LENGTH];
        $data = $decryptedValues[ComputopApiConfig::DATA];

        $computopPaymentTransfer->setData($data);
        $computopPaymentTransfer->setLen($length);
        $urlToComputop = $this->getUrlToComputop($this->getActionUrl(), $computopPaymentTransfer->getMerchantId(), $data, $length);
        $computopPaymentTransfer->setUrl($urlToComputop);

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopSofortPaymentTransfer $computopSofortPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(TransferInterface $computopSofortPaymentTransfer): array
    {
        /** @var \Generated\Shared\Transfer\ComputopSofortPaymentTransfer $computopSofortPaymentTransfer */
        $dataSubArray[ComputopApiConfig::TRANS_ID] = $computopSofortPaymentTransfer->getTransId();
        $dataSubArray[ComputopApiConfig::AMOUNT] = $computopSofortPaymentTransfer->getAmount();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $computopSofortPaymentTransfer->getCurrency();
        $dataSubArray[ComputopApiConfig::URL_SUCCESS] = $computopSofortPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopApiConfig::URL_NOTIFY] = $computopSofortPaymentTransfer->getUrlNotify();
        $dataSubArray[ComputopApiConfig::URL_FAILURE] = $computopSofortPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopApiConfig::RESPONSE] = $computopSofortPaymentTransfer->getResponse();
        $dataSubArray[ComputopApiConfig::MAC] = $computopSofortPaymentTransfer->getMac();
        $dataSubArray[ComputopApiConfig::ORDER_DESC] = $computopSofortPaymentTransfer->getOrderDesc();
        $dataSubArray[ComputopApiConfig::ETI_ID] = $this->config->getEtiId();
        $dataSubArray[ComputopApiConfig::IP_ADDRESS] = $computopSofortPaymentTransfer->getClientIp();
        $dataSubArray[ComputopApiConfig::SHIPPING_ZIP] = $computopSofortPaymentTransfer->getShippingZip();

        return $dataSubArray;
    }

    /**
     * @return string
     */
    protected function getActionUrl(): string
    {
        return $this->config->getSofortInitAction();
    }
}
