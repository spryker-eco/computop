<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use ArrayObject;
use Generated\Shared\Transfer\ComputopPayPalPaymentTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Router\Router\Router;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin;

class PayPalMapper extends AbstractMapper
{
    /**
     * @var int
     */
    protected const PAYPAL_ITEM_DESCRIPTION_OFFSET = 2;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer
     */
    public function createComputopPaymentTransfer(QuoteTransfer $quoteTransfer): ComputopPayPalPaymentTransfer
    {
        /** @var \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer $computopPaymentTransfer */
        $computopPaymentTransfer = parent::createComputopPaymentTransfer($quoteTransfer);
        $computopPaymentTransfer->setMac(
            $this->computopApiService->generateEncryptedMac(
                $this->createRequestTransfer($computopPaymentTransfer),
            ),
        );

        $computopPaymentTransfer = $this->addOrderDescriptions(
            $computopPaymentTransfer,
            $quoteTransfer->getItems(),
        );

        $computopPaymentTransfer->setTaxTotal($quoteTransfer->getTotals()->getTaxTotal()->getAmount());
        $computopPaymentTransfer->setShAmount($quoteTransfer->getTotals()->getShipmentTotal());

        $itemTotal = $this->calculateItemTotal($quoteTransfer);
        $computopPaymentTransfer->setItemTotal($itemTotal);

        $decryptedValues = $this->computopApiService->getEncryptedArray(
            $this->getDataSubArray($computopPaymentTransfer),
            $this->config->getBlowfishPassword(),
        );

        $computopPaymentTransfer->setData($decryptedValues[ComputopApiConfig::DATA]);
        $computopPaymentTransfer->setLen($decryptedValues[ComputopApiConfig::LENGTH]);
        $computopPaymentTransfer->setUrl(
            $this->getActionUrl(
                $this->config->getPayPalInitActionUrl(),
                $this->getQueryParameters(
                    $computopPaymentTransfer->getMerchantId(),
                    $decryptedValues[ComputopApiConfig::DATA],
                    $decryptedValues[ComputopApiConfig::LENGTH],
                ),
            ),
        );

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function calculateItemTotal(QuoteTransfer $quoteTransfer): int
    {
        return $quoteTransfer->getTotals()->getGrandTotal() -
            $quoteTransfer->getTotals()->getShipmentTotal() -
            $quoteTransfer->getTotals()->getTaxTotal()->getAmount();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(QuoteTransfer $quoteTransfer): ComputopPayPalPaymentTransfer
    {
        $computopPaymentTransfer = new ComputopPayPalPaymentTransfer();

        $computopPaymentTransfer->setCapture(
            $this->getCaptureType(ComputopSharedConfig::PAYMENT_METHOD_PAY_PAL),
        );
        $computopPaymentTransfer->setTransId($this->generateTransId($quoteTransfer));
        $computopPaymentTransfer->setTxType($this->config->getPayPalTxType());
        $computopPaymentTransfer->setUrlSuccess(
            $this->router->generate(ComputopRouteProviderPlugin::PAY_PAL_SUCCESS, [], Router::ABSOLUTE_URL),
        );
        $computopPaymentTransfer->setOrderDesc(
            $this->computopApiService->getDescriptionValue($quoteTransfer->getItems()->getArrayCopy()),
        );

        $this->mapAddressFromQuoteToComputopPayPalPayment($quoteTransfer, $computopPaymentTransfer);

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer $computopPayPalPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer
     */
    protected function mapAddressFromQuoteToComputopPayPalPayment(
        QuoteTransfer $quoteTransfer,
        ComputopPayPalPaymentTransfer $computopPayPalPaymentTransfer
    ): ComputopPayPalPaymentTransfer {
        $addressTransfer = $quoteTransfer->getBillingSameAsShipping()
            ? $quoteTransfer->getBillingAddress()
            : $this->getShippingAddressFromQuote($quoteTransfer);

        if (!$addressTransfer) {
            return $computopPayPalPaymentTransfer;
        }

        $computopPayPalPaymentTransfer
            ->setFirstName($addressTransfer->getFirstName())
            ->setLastName($addressTransfer->getLastName())
            ->setAddressStreet($addressTransfer->getAddress1())
            ->setAddressStreet2($addressTransfer->getAddress2())
            ->setAddressCity($addressTransfer->getCity())
            ->setAddressState($addressTransfer->getState())
            ->setAddressZip($addressTransfer->getZipCode())
            ->setAddressCountryCode($addressTransfer->getIso2Code())
            ->setPhone($addressTransfer->getPhone());

        return $computopPayPalPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer $computopPayPalPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(ComputopPayPalPaymentTransfer $computopPayPalPaymentTransfer): array
    {
        $dataSubArray = [];
        $dataSubArray[ComputopApiConfig::TRANS_ID] = $computopPayPalPaymentTransfer->getTransId();
        $dataSubArray[ComputopApiConfig::AMOUNT] = $computopPayPalPaymentTransfer->getAmount();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $computopPayPalPaymentTransfer->getCurrency();
        $dataSubArray[ComputopApiConfig::CAPTURE] = $computopPayPalPaymentTransfer->getCapture();
        $dataSubArray[ComputopApiConfig::TX_TYPE] = $computopPayPalPaymentTransfer->getTxType();
        $dataSubArray[ComputopApiConfig::ORDER_DESC] = $computopPayPalPaymentTransfer->getOrderDesc();

        $orderDescriptions = $computopPayPalPaymentTransfer->getOrderDescriptions();
        $dataSubArray = $this->addOrderItemDescriptions($dataSubArray, $orderDescriptions);

        $dataSubArray[ComputopApiConfig::TAX_TOTAL] = $computopPayPalPaymentTransfer->getTaxTotal();
        $dataSubArray[ComputopApiConfig::ITEM_TOTAL] = $computopPayPalPaymentTransfer->getItemTotal();
        $dataSubArray[ComputopApiConfig::SHIPPING_AMOUNT] = $computopPayPalPaymentTransfer->getShAmount();

        $dataSubArray[ComputopApiConfig::MAC] = $computopPayPalPaymentTransfer->getMac();
        $dataSubArray[ComputopApiConfig::URL_SUCCESS] = $computopPayPalPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopApiConfig::URL_FAILURE] = $computopPayPalPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopApiConfig::RESPONSE] = $computopPayPalPaymentTransfer->getResponse();
        $dataSubArray[ComputopApiConfig::URL_NOTIFY] = $computopPayPalPaymentTransfer->getUrlNotify();
        $dataSubArray[ComputopApiConfig::REQ_ID] = $computopPayPalPaymentTransfer->getReqId();

        $dataSubArray[ComputopApiConfig::ETI_ID] = $this->config->getEtiId();
        $dataSubArray[ComputopApiConfig::IP_ADDRESS] = $computopPayPalPaymentTransfer->getClientIp();
        $dataSubArray[ComputopApiConfig::SHIPPING_ZIP] = $computopPayPalPaymentTransfer->getShippingZip();

        if (!$computopPayPalPaymentTransfer->getAddressStreet()) {
            $dataSubArray[ComputopApiConfig::NO_SHIPPING] = ComputopSharedConfig::PAY_PAL_NO_SHIPPING;

            return $dataSubArray;
        }

        $dataSubArray[ComputopApiConfig::FIRST_NAME] = $computopPayPalPaymentTransfer->getFirstName();
        $dataSubArray[ComputopApiConfig::LAST_NAME] = $computopPayPalPaymentTransfer->getLastName();
        $dataSubArray[ComputopApiConfig::ADDRESS_STREET] = $computopPayPalPaymentTransfer->getAddressStreet();
        $dataSubArray[ComputopApiConfig::ADDRESS_STREET2] = $computopPayPalPaymentTransfer->getAddressStreet2();
        $dataSubArray[ComputopApiConfig::ADDRESS_CITY] = $computopPayPalPaymentTransfer->getAddressCity();

        if ($computopPayPalPaymentTransfer->getAddressState()) {
            $dataSubArray[ComputopApiConfig::ADDRESS_STATE] = $computopPayPalPaymentTransfer->getAddressState();
        }

        $dataSubArray[ComputopApiConfig::ADDRESS_ZIP] = $computopPayPalPaymentTransfer->getAddressZip();
        $dataSubArray[ComputopApiConfig::ADDRESS_COUNTRY_CODE] = $computopPayPalPaymentTransfer->getAddressCountryCode();

        if ($computopPayPalPaymentTransfer->getPhone()) {
            $dataSubArray[ComputopApiConfig::PHONE] = $computopPayPalPaymentTransfer->getPhone();
        }

        return $dataSubArray;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getOrderItemDescription(ItemTransfer $itemTransfer): string
    {
        return sprintf(
            '%s,%f,%s,%d,,%f',
            $itemTransfer->getName(),
            $itemTransfer->getSumPrice(),
            $itemTransfer->getSku(),
            $itemTransfer->getQuantity(),
            $itemTransfer->getUnitTaxAmount(),
        );
    }

    /**
     * Order item description for items should start from OrderDesc2 to OrderDesc99.
     *
     * @param int $key
     *
     * @return string
     */
    protected function getOrderItemDescriptionKey(int $key): string
    {
        return ComputopApiConfig::ORDER_DESC . $key;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer $computopPaymentTransfer
     * @param \ArrayObject<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer
     */
    protected function addOrderDescriptions(
        ComputopPayPalPaymentTransfer $computopPaymentTransfer,
        ArrayObject $itemTransfers
    ): ComputopPayPalPaymentTransfer {
        if ($itemTransfers->count() > $this->config->getMaxOrderDescriptionItemsForPayPalPaymentPage()) {
            return $computopPaymentTransfer;
        }

        foreach ($itemTransfers as $itemTransfer) {
            $orderDescription = $this->getOrderItemDescription($itemTransfer);
            $computopPaymentTransfer->addOrderDescriptions($orderDescription);
        }

        return $computopPaymentTransfer;
    }

    /**
     * @param array $dataSubArray
     * @param array $orderDescriptions
     *
     * @return array
     */
    protected function addOrderItemDescriptions(array $dataSubArray, array $orderDescriptions): array
    {
        foreach ($orderDescriptions as $key => $orderDescription) {
            $orderDescriptionIndex = static::PAYPAL_ITEM_DESCRIPTION_OFFSET + $key;
            $orderDescriptionKey = $this->getOrderItemDescriptionKey($orderDescriptionIndex);
            $dataSubArray[$orderDescriptionKey] = $orderDescription;
        }

        return $dataSubArray;
    }
}
