<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Communication\Hook\Mapper\Order;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class PaydirektMapper extends AbstractMapper
{
    /**
     * @return string
     */
    public function getMethodName()
    {
        return ComputopConfig::PAYMENT_METHOD_PAYDIREKT;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPaymentTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function updateComputopPaymentTransfer(TransferInterface $quoteTransfer, TransferInterface $computopPaymentTransfer)
    {
        $computopPaymentTransfer->setMerchantId($this->config->getMerchantId());
        $computopPaymentTransfer->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        $computopPaymentTransfer->setMac(
            $this->computopService->getMacEncryptedValue($computopPaymentTransfer)
        );

        $decryptedValues = $this->computopService->getEncryptedArray(
            $this->getDataSubArray($computopPaymentTransfer),
            Config::get(ComputopConstants::BLOWFISH_PASSWORD)
        );

        $length = $decryptedValues[ComputopApiConfig::LENGTH];
        $data = $decryptedValues[ComputopApiConfig::DATA];

        $computopPaymentTransfer->setData($data);
        $computopPaymentTransfer->setLen($length);
        $computopPaymentTransfer->setUrl($this->getUrlToComputop($computopPaymentTransfer->getMerchantId(), $data, $length));

        return $computopPaymentTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $cardPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(TransferInterface $cardPaymentTransfer)
    {
        /** @var \Generated\Shared\Transfer\ComputopPaydirektPaymentTransfer $cardPaymentTransfer */
        $dataSubArray[ComputopApiConfig::TRANS_ID] = $cardPaymentTransfer->getTransId();
        $dataSubArray[ComputopApiConfig::AMOUNT] = $cardPaymentTransfer->getAmount();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $cardPaymentTransfer->getCurrency();
        $dataSubArray[ComputopApiConfig::URL_SUCCESS] = $cardPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopApiConfig::URL_FAILURE] = $cardPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopApiConfig::RESPONSE] = $cardPaymentTransfer->getResponse();
        $dataSubArray[ComputopApiConfig::MAC] = $cardPaymentTransfer->getMac();
        $dataSubArray[ComputopApiConfig::ORDER_DESC] = $cardPaymentTransfer->getOrderDesc();

        $dataSubArray[ComputopApiConfig::SHOP_API_KEY] = $cardPaymentTransfer->getShopApiKey();
        $dataSubArray[ComputopApiConfig::SHIPPING_FIRST_NAME] = $cardPaymentTransfer->getShippingFirstName();
        $dataSubArray[ComputopApiConfig::SHIPPING_LAST_NAME] = $cardPaymentTransfer->getShippingLastName();
        $dataSubArray[ComputopApiConfig::SHIPPING_ZIP] = $cardPaymentTransfer->getShippingZip();
        $dataSubArray[ComputopApiConfig::SHIPPING_CITY] = $cardPaymentTransfer->getShippingCity();
        $dataSubArray[ComputopApiConfig::SHIPPING_COUNTRY_CODE] = $cardPaymentTransfer->getShippingCountryCode();
        $dataSubArray[ComputopApiConfig::SHIPPING_AMOUNT] = $cardPaymentTransfer->getShippingAmount();
        $dataSubArray[ComputopApiConfig::SHOPPING_BASKET_AMOUNT] = $cardPaymentTransfer->getShoppingBasketAmount();

        return $dataSubArray;
    }

    /**
     * @return string
     */
    protected function getActionUrl()
    {
        return Config::get(ComputopConstants::PAYDIREKT_ORDER_ACTION);
    }
}
