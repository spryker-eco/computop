<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Hook\Mapper\Order;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Shared\Computop\Config\ComputopFieldName;

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

        $length = $decryptedValues[ComputopFieldName::LENGTH];
        $data = $decryptedValues[ComputopFieldName::DATA];

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
        $dataSubArray[ComputopFieldName::TRANS_ID] = $cardPaymentTransfer->getTransId();
        $dataSubArray[ComputopFieldName::AMOUNT] = $cardPaymentTransfer->getAmount();
        $dataSubArray[ComputopFieldName::CURRENCY] = $cardPaymentTransfer->getCurrency();
        $dataSubArray[ComputopFieldName::URL_SUCCESS] = $cardPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopFieldName::URL_FAILURE] = $cardPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopFieldName::RESPONSE] = $cardPaymentTransfer->getResponse();
        $dataSubArray[ComputopFieldName::MAC] = $cardPaymentTransfer->getMac();
        $dataSubArray[ComputopFieldName::ORDER_DESC] = $cardPaymentTransfer->getOrderDesc();

        $dataSubArray[ComputopFieldName::SHOP_API_KEY] = $cardPaymentTransfer->getShopApiKey();
        $dataSubArray[ComputopFieldName::SHIPPING_FIRST_NAME] = $cardPaymentTransfer->getShippingFirstName();
        $dataSubArray[ComputopFieldName::SHIPPING_LAST_NAME] = $cardPaymentTransfer->getShippingLastName();
        $dataSubArray[ComputopFieldName::SHIPPING_ZIP] = $cardPaymentTransfer->getShippingZip();
        $dataSubArray[ComputopFieldName::SHIPPING_CITY] = $cardPaymentTransfer->getShippingCity();
        $dataSubArray[ComputopFieldName::SHIPPING_COUNTRY_CODE] = $cardPaymentTransfer->getShippingCountryCode();
        $dataSubArray[ComputopFieldName::SHIPPING_AMOUNT] = $cardPaymentTransfer->getShippingAmount();
        $dataSubArray[ComputopFieldName::SHOPPING_BASKET_AMOUNT] = $cardPaymentTransfer->getShoppingBasketAmount();

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
