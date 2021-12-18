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
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPaydirectPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(TransferInterface $computopPaydirectPaymentTransfer): array
    {
        /** @var \Generated\Shared\Transfer\ComputopPaydirektPaymentTransfer $computopPaydirectPaymentTransfer */
        $dataSubArray = [];
        $dataSubArray[ComputopApiConfig::TRANS_ID] = $computopPaydirectPaymentTransfer->getTransId();
        $dataSubArray[ComputopApiConfig::AMOUNT] = $computopPaydirectPaymentTransfer->getAmount();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $computopPaydirectPaymentTransfer->getCurrency();
        $dataSubArray[ComputopApiConfig::URL_SUCCESS] = $computopPaydirectPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopApiConfig::URL_NOTIFY] = $computopPaydirectPaymentTransfer->getUrlNotify();
        $dataSubArray[ComputopApiConfig::URL_FAILURE] = $computopPaydirectPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopApiConfig::RESPONSE] = $computopPaydirectPaymentTransfer->getResponse();
        $dataSubArray[ComputopApiConfig::MAC] = $computopPaydirectPaymentTransfer->getMac();
        $dataSubArray[ComputopApiConfig::ORDER_DESC] = $computopPaydirectPaymentTransfer->getOrderDesc();

        $dataSubArray[ComputopApiConfig::SHOP_API_KEY] = $computopPaydirectPaymentTransfer->getShopApiKey();
        $dataSubArray[ComputopApiConfig::SHIPPING_FIRST_NAME] = $computopPaydirectPaymentTransfer->getShippingFirstName();
        $dataSubArray[ComputopApiConfig::SHIPPING_LAST_NAME] = $computopPaydirectPaymentTransfer->getShippingLastName();
        $dataSubArray[ComputopApiConfig::SHIPPING_ZIP] = $computopPaydirectPaymentTransfer->getShippingZip();
        $dataSubArray[ComputopApiConfig::SHIPPING_CITY] = $computopPaydirectPaymentTransfer->getShippingCity();
        $dataSubArray[ComputopApiConfig::SHIPPING_COUNTRY_CODE] = $computopPaydirectPaymentTransfer->getShippingCountryCode();
        $dataSubArray[ComputopApiConfig::SHIPPING_AMOUNT] = $computopPaydirectPaymentTransfer->getShippingAmount();
        $dataSubArray[ComputopApiConfig::SHOPPING_BASKET_AMOUNT] = $computopPaydirectPaymentTransfer->getShoppingBasketAmount();
        $dataSubArray[ComputopApiConfig::ETI_ID] = $this->config->getEtiId();
        $dataSubArray[ComputopApiConfig::IP_ADDRESS] = $computopPaydirectPaymentTransfer->getClientIp();
        $dataSubArray[ComputopApiConfig::SHIPPING_ZIP] = $computopPaydirectPaymentTransfer->getShippingZip();

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
