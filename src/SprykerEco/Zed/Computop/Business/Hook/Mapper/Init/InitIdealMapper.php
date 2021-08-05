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
        $urlToComputop = $this->getUrlToComputop($this->getActionUrl(), (string)$computopPaymentTransfer->getMerchantId(), $data, $length);
        $computopPaymentTransfer->setUrl($urlToComputop);

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
        $dataSubArray[ComputopApiConfig::TRANS_ID] = $computopIdealPaymentTransfer->getTransId();
        $dataSubArray[ComputopApiConfig::AMOUNT] = $computopIdealPaymentTransfer->getAmount();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $computopIdealPaymentTransfer->getCurrency();
        $dataSubArray[ComputopApiConfig::URL_SUCCESS] = $computopIdealPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopApiConfig::URL_NOTIFY] = $computopIdealPaymentTransfer->getUrlNotify();
        $dataSubArray[ComputopApiConfig::URL_FAILURE] = $computopIdealPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopApiConfig::RESPONSE] = $computopIdealPaymentTransfer->getResponse();
        $dataSubArray[ComputopApiConfig::MAC] = $computopIdealPaymentTransfer->getMac();
        $dataSubArray[ComputopApiConfig::ORDER_DESC] = $computopIdealPaymentTransfer->getOrderDesc();
        $dataSubArray[ComputopApiConfig::ETI_ID] = $this->config->getEtiId();
        $dataSubArray[ComputopApiConfig::IP_ADDRESS] = $computopIdealPaymentTransfer->getClientIp();
        $dataSubArray[ComputopApiConfig::SHIPPING_ZIP] = $computopIdealPaymentTransfer->getShippingZip();
        $dataSubArray[ComputopApiConfig::ISSUER_ID] = $this->config->getIdealIssuerId();

        return $dataSubArray;
    }

    /**
     * @return string
     */
    protected function getActionUrl(): string
    {
        return $this->config->getIdealInitAction();
    }
}
