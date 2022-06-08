<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Hook\Mapper\Init;

use Generated\Shared\Transfer\ComputopPaydirektPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class InitPaydirektMapper extends AbstractMapper
{
    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return ComputopConfig::PAYMENT_METHOD_PAYDIREKT;
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
        /** @var \Generated\Shared\Transfer\ComputopPaydirektPaymentTransfer $computopPaymentTransfer */
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
     * @param \Generated\Shared\Transfer\ComputopPaydirektPaymentTransfer $computopPaydirectPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(ComputopPaydirektPaymentTransfer $computopPaydirectPaymentTransfer): array
    {
        $dataSubArray = [
            ComputopApiConfig::TRANS_ID => $computopPaydirectPaymentTransfer->getTransId(),
            ComputopApiConfig::AMOUNT => $computopPaydirectPaymentTransfer->getAmount(),
            ComputopApiConfig::CURRENCY => $computopPaydirectPaymentTransfer->getCurrency(),
            ComputopApiConfig::URL_SUCCESS => $computopPaydirectPaymentTransfer->getUrlSuccess(),
            ComputopApiConfig::URL_NOTIFY => $computopPaydirectPaymentTransfer->getUrlNotify(),
            ComputopApiConfig::URL_FAILURE => $computopPaydirectPaymentTransfer->getUrlFailure(),
            ComputopApiConfig::RESPONSE => $computopPaydirectPaymentTransfer->getResponse(),
            ComputopApiConfig::MAC => $computopPaydirectPaymentTransfer->getMac(),
            ComputopApiConfig::ORDER_DESC => $computopPaydirectPaymentTransfer->getOrderDesc(),
            ComputopApiConfig::SHOP_API_KEY => $computopPaydirectPaymentTransfer->getShopApiKey(),
            ComputopApiConfig::SHIPPING_FIRST_NAME => $computopPaydirectPaymentTransfer->getShippingFirstName(),
            ComputopApiConfig::SHIPPING_LAST_NAME => $computopPaydirectPaymentTransfer->getShippingLastName(),
            ComputopApiConfig::SHIPPING_ZIP => $computopPaydirectPaymentTransfer->getShippingZip(),
            ComputopApiConfig::SHIPPING_CITY => $computopPaydirectPaymentTransfer->getShippingCity(),
            ComputopApiConfig::SHIPPING_COUNTRY_CODE => $computopPaydirectPaymentTransfer->getShippingCountryCode(),
            ComputopApiConfig::SHOPPING_BASKET_AMOUNT => $computopPaydirectPaymentTransfer->getShoppingBasketAmount(),
            ComputopApiConfig::ETI_ID => $this->config->getEtiId(),
            ComputopApiConfig::IP_ADDRESS => $computopPaydirectPaymentTransfer->getClientIp(),
        ];

        if ($computopPaydirectPaymentTransfer->getShippingAmount()) {
            $dataSubArray[ComputopApiConfig::SHIPPING_AMOUNT] = $computopPaydirectPaymentTransfer->getShippingAmount();
        }

        return $dataSubArray;
    }

    /**
     * @return string
     */
    protected function getActionUrl(): string
    {
        return $this->config->getPaydirektInitAction();
    }
}
