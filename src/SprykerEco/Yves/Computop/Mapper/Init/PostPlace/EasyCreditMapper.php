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
    public function createComputopPaymentTransfer(QuoteTransfer $quoteTransfer)
    {
        /** @var \Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer $computopPaymentTransfer */
        $computopPaymentTransfer = parent::createComputopPaymentTransfer($quoteTransfer);

        $addressData = $this->getAdditionalAddressData($quoteTransfer);
        $computopPaymentTransfer = $this->mapAddressDataToEasyCreditPayment($addressData, $computopPaymentTransfer);

        $computopPaymentTransfer->setUrlNotify(
            $this->router->generate(ComputopRouteProviderPlugin::NOTIFY_PATH_NAME, [], Router::ABSOLUTE_URL)
        );
        $computopPaymentTransfer->setMac(
            $this->computopApiService->generateEncryptedMac(
                $this->createRequestTransfer($computopPaymentTransfer)
            )
        );

        $decryptedValues = $this->computopApiService->getEncryptedArray(
            $this->getDataSubArray($computopPaymentTransfer),
            $this->config->getBlowfishPassword()
        );

        $computopPaymentTransfer->setData($decryptedValues[ComputopApiConfig::DATA]);
        $computopPaymentTransfer->setLen($decryptedValues[ComputopApiConfig::LENGTH]);
        $computopPaymentTransfer->setUrl(
            $this->getActionUrl(
                $this->config->getEasyCreditInitActionUrl(),
                $this->getQueryParameters(
                    $computopPaymentTransfer->getMerchantId(),
                    $decryptedValues[ComputopApiConfig::DATA],
                    $decryptedValues[ComputopApiConfig::LENGTH]
                )
            )
        );

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(QuoteTransfer $quoteTransfer)
    {
        $computopPaymentTransfer = new ComputopEasyCreditPaymentTransfer();

        $computopPaymentTransfer->setTransId($this->generateTransId($quoteTransfer));
        $computopPaymentTransfer->setUrlSuccess(
            $this->router->generate(ComputopRouteProviderPlugin::EASY_CREDIT_SUCCESS, [], Router::ABSOLUTE_URL)
        );

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer $cardPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(ComputopEasyCreditPaymentTransfer $cardPaymentTransfer)
    {
        $dataSubArray[ComputopApiConfig::TRANS_ID] = $cardPaymentTransfer->getTransId();
        $dataSubArray[ComputopApiConfig::AMOUNT] = $cardPaymentTransfer->getAmount();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $cardPaymentTransfer->getCurrency();
        $dataSubArray[ComputopApiConfig::URL_SUCCESS] = $cardPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopApiConfig::URL_FAILURE] = $cardPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopApiConfig::URL_NOTIFY] = $cardPaymentTransfer->getUrlNotify();
        $dataSubArray[ComputopApiConfig::RESPONSE] = $cardPaymentTransfer->getResponse();
        $dataSubArray[ComputopApiConfig::EVENT_TOKEN] = ComputopApiConfig::EVENT_TOKEN_INIT;
        $dataSubArray[ComputopApiConfig::ETI_ID] = $this->config->getEtiId();
        $dataSubArray[ComputopApiConfig::IP_ADDRESS] = $cardPaymentTransfer->getClientIp();
        $dataSubArray[ComputopApiConfig::SHIPPING_ZIP] = $cardPaymentTransfer->getShippingZip();
        $dataSubArray[ComputopApiConfig::SHIPPING_CITY] = $cardPaymentTransfer->getShippingCity();
        $dataSubArray[ComputopApiConfig::SHIPPING_COUNTRY_CODE] = $cardPaymentTransfer->getShippingCountryCode();
        $dataSubArray[ComputopApiConfig::SHIPPING_STREET] = $cardPaymentTransfer->getShippingStreet();
        $dataSubArray[ComputopApiConfig::SHIPPING_STREET_NUMBER] = $cardPaymentTransfer->getShippingStreetNumber();

        return $dataSubArray;
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

        return $computopEasyCreditPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getAdditionalAddressData(QuoteTransfer $quoteTransfer): array
    {
        $addressTransfer = $quoteTransfer->getBillingSameAsShipping() ? $quoteTransfer->getBillingAddress() : $quoteTransfer->getShippingAddress();

        $addressData[ComputopEasyCreditPaymentTransfer::SHIPPING_CITY] = $addressTransfer->getCity();
        $addressData[ComputopEasyCreditPaymentTransfer::SHIPPING_STREET] = $addressTransfer->getAddress1();
        $addressData[ComputopEasyCreditPaymentTransfer::SHIPPING_STREET_NUMBER] = $addressTransfer->getAddress2();
        $addressData[ComputopEasyCreditPaymentTransfer::SHIPPING_COUNTRY_CODE] = $addressTransfer->getIso2Code();

        return $addressData;
    }
}
