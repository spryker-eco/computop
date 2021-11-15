<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Router\Router\Router;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin;

class EasyCreditMapper extends AbstractMapper
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer
     */
    public function createComputopPaymentTransfer(QuoteTransfer $quoteTransfer): ComputopEasyCreditPaymentTransfer
    {
        /** @var \Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer $computopPaymentTransfer */
        $computopPaymentTransfer = parent::createComputopPaymentTransfer($quoteTransfer);

        $addressData = $this->getAdditionalAddressData($quoteTransfer);
        $computopPaymentTransfer = $this->mapAddressDataToEasyCreditPayment($addressData, $computopPaymentTransfer);
        $computopPaymentTransfer->setMac(
            $this->computopApiService->generateEncryptedMac(
                $this->createRequestTransfer($computopPaymentTransfer),
            ),
        );

        $decryptedValues = $this->computopApiService->getEncryptedArray(
            $this->getDataSubArray($computopPaymentTransfer),
            $this->config->getBlowfishPassword(),
        );

        $computopPaymentTransfer->setData($decryptedValues[ComputopApiConfig::DATA]);
        $computopPaymentTransfer->setLen($decryptedValues[ComputopApiConfig::LENGTH]);
        $computopPaymentTransfer->setUrl(
            $this->getActionUrl(
                $this->config->getEasyCreditInitActionUrl(),
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
     * @return \Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(QuoteTransfer $quoteTransfer): ComputopEasyCreditPaymentTransfer
    {
        $computopPaymentTransfer = new ComputopEasyCreditPaymentTransfer();

        $computopPaymentTransfer->setTransId($this->generateTransId($quoteTransfer));
        $computopPaymentTransfer->setUrlSuccess(
            $this->router->generate(ComputopRouteProviderPlugin::EASY_CREDIT_SUCCESS, [], Router::ABSOLUTE_URL),
        );

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer $cardPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(ComputopEasyCreditPaymentTransfer $cardPaymentTransfer): array
    {
        return [
            ComputopApiConfig::MERCHANT_ID => $cardPaymentTransfer->getMerchantId(),
            ComputopApiConfig::TRANS_ID => $cardPaymentTransfer->getTransId(),
            ComputopApiConfig::AMOUNT => $cardPaymentTransfer->getAmount(),
            ComputopApiConfig::CURRENCY => $cardPaymentTransfer->getCurrency(),
            ComputopApiConfig::MAC => $cardPaymentTransfer->getMac(),
            ComputopApiConfig::URL_SUCCESS => $cardPaymentTransfer->getUrlSuccess(),
            ComputopApiConfig::URL_NOTIFY => $cardPaymentTransfer->getUrlNotify(),
            ComputopApiConfig::URL_FAILURE => $cardPaymentTransfer->getUrlFailure(),
            ComputopApiConfig::RESPONSE => $cardPaymentTransfer->getResponse(),
            ComputopApiConfig::EVENT_TOKEN => ComputopApiConfig::EVENT_TOKEN_INIT,
            ComputopApiConfig::ETI_ID => $this->config->getEtiId(),
            ComputopApiConfig::IP_ADDRESS => $cardPaymentTransfer->getClientIp(),
            ComputopApiConfig::SHIPPING_ZIP => $cardPaymentTransfer->getShippingZip(),
            ComputopApiConfig::SHIPPING_CITY => $cardPaymentTransfer->getShippingCity(),
            ComputopApiConfig::SHIPPING_COUNTRY_CODE => $cardPaymentTransfer->getShippingCountryCode(),
            ComputopApiConfig::SHIPPING_STREET => $cardPaymentTransfer->getShippingStreet(),
            ComputopApiConfig::SHIPPING_STREET_NUMBER => $cardPaymentTransfer->getShippingStreetNumber(),
            ComputopApiConfig::SHIPPING_ADDRESS_ADDITION => $cardPaymentTransfer->getShippingAddressAddition(),
        ];
    }

    /**
     * @param array $addressData
     * @param \Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer $computopEasyCreditPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer
     */
    protected function mapAddressDataToEasyCreditPayment(
        array $addressData,
        ComputopEasyCreditPaymentTransfer $computopEasyCreditPaymentTransfer
    ): ComputopEasyCreditPaymentTransfer {
        $computopEasyCreditPaymentTransfer->setShippingCity($addressData[ComputopEasyCreditPaymentTransfer::SHIPPING_CITY]);
        $computopEasyCreditPaymentTransfer->setShippingStreet($addressData[ComputopEasyCreditPaymentTransfer::SHIPPING_STREET]);
        $computopEasyCreditPaymentTransfer->setShippingStreetNumber($addressData[ComputopEasyCreditPaymentTransfer::SHIPPING_STREET_NUMBER]);
        $computopEasyCreditPaymentTransfer->setShippingCountryCode($addressData[ComputopEasyCreditPaymentTransfer::SHIPPING_COUNTRY_CODE]);
        $computopEasyCreditPaymentTransfer->setShippingAddressAddition('Computop GmbH');
        $computopEasyCreditPaymentTransfer->setPackingStation('NO');

        return $computopEasyCreditPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getAdditionalAddressData(QuoteTransfer $quoteTransfer): array
    {
        $addressTransfer = $quoteTransfer->getBillingSameAsShipping()
            ? $quoteTransfer->getBillingAddress()
            : $this->getShippingAddressFromQuote($quoteTransfer);

        $addressData = [];
        $addressData[ComputopEasyCreditPaymentTransfer::SHIPPING_CITY] = $addressTransfer->getCity();
        $addressData[ComputopEasyCreditPaymentTransfer::SHIPPING_STREET] = $addressTransfer->getAddress1();
        $addressData[ComputopEasyCreditPaymentTransfer::SHIPPING_STREET_NUMBER] = $addressTransfer->getAddress2();
        $addressData[ComputopEasyCreditPaymentTransfer::SHIPPING_COUNTRY_CODE] = $addressTransfer->getIso2Code();

        return $addressData;
    }
}
