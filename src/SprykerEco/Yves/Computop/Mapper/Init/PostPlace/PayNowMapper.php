<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Generated\Shared\Transfer\ComputopAddressLineTransfer;
use Generated\Shared\Transfer\ComputopAddressTransfer;
use Generated\Shared\Transfer\ComputopBrowserInfoTransfer;
use Generated\Shared\Transfer\ComputopConsumerTransfer;
use Generated\Shared\Transfer\ComputopCountryTransfer;
use Generated\Shared\Transfer\ComputopCredentialOnFileTransfer;
use Generated\Shared\Transfer\ComputopCredentialOnFileTypeTransfer;
use Generated\Shared\Transfer\ComputopCustomerInfoTransfer;
use Generated\Shared\Transfer\ComputopPayNowPaymentTransfer;
use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Router\Router\Router;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin;

class PayNowMapper extends AbstractMapper
{
    protected const HEADER_USER_AGENT = 'User-Agent';
    protected const HEADER_ACCEPT = 'Accept';
    protected const IFRAME_COLOR_DEPTH = 24;
    protected const IFRAME_SCREEN_HEIGHT  = 723;
    protected const IFRAME_SCREEN_WIDTH = 1536;
    protected const IFRAME_TIME_ZONE_OFFSET = '300';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer
     */
    public function createComputopPaymentTransfer(QuoteTransfer $quoteTransfer)
    {
        /** @var \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $computopPaymentTransfer */
        $computopPaymentTransfer = parent::createComputopPaymentTransfer($quoteTransfer);
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
        $computopPaymentTransfer->setUrl($this->config->getPayNowInitActionUrl());

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(QuoteTransfer $quoteTransfer)
    {
        $computopPaymentTransfer = new ComputopPayNowPaymentTransfer();

        $computopPaymentTransfer->setCapture(
            $this->getCaptureType(ComputopSharedConfig::PAYMENT_METHOD_PAY_NOW)
        );
        $computopPaymentTransfer->setTransId($this->generateTransId($quoteTransfer));
        $computopPaymentTransfer->setTxType($this->config->getPayNowTxType());
        $computopPaymentTransfer->setUrlSuccess(
            $this->router->generate(ComputopRouteProviderPlugin::PAY_NOW_SUCCESS, [], Router::ABSOLUTE_URL)
        );
        $computopPaymentTransfer->setOrderDesc(
            $this->computopApiService->getDescriptionValue($quoteTransfer->getItems()->getArrayCopy())
        );

        $computopPaymentTransfer = $this->expandPayNowPaymentWithShipToCustomer($computopPaymentTransfer, $quoteTransfer);
        $computopPaymentTransfer = $this->expandPayNowPaymentWithBillToCustomer($computopPaymentTransfer, $quoteTransfer);
        $computopPaymentTransfer = $this->expandPayNowPaymentWithShippingAddress($computopPaymentTransfer, $quoteTransfer);
        $computopPaymentTransfer = $this->expandPayNowPaymentWithBillingAddress($computopPaymentTransfer, $quoteTransfer);
        $computopPaymentTransfer = $this->expandPayNowPaymentWithCredentialOnFIle($computopPaymentTransfer);
        $computopPaymentTransfer = $this->expandPayNowPaymentWithBrowserInfo($computopPaymentTransfer);

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer)
    {
        $dataSubArray[ComputopApiConfig::TRANS_ID] = $computopPayNowPaymentTransfer->getTransId();
        $dataSubArray[ComputopApiConfig::AMOUNT] = $computopPayNowPaymentTransfer->getAmount();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $computopPayNowPaymentTransfer->getCurrency();
        $dataSubArray[ComputopApiConfig::URL_SUCCESS] = $computopPayNowPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopApiConfig::URL_NOTIFY] = $computopPayNowPaymentTransfer->getUrlNotify();
        $dataSubArray[ComputopApiConfig::URL_FAILURE] = $computopPayNowPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopApiConfig::CAPTURE] = $computopPayNowPaymentTransfer->getCapture();
        $dataSubArray[ComputopApiConfig::RESPONSE] = $computopPayNowPaymentTransfer->getResponse();
        $dataSubArray[ComputopApiConfig::MAC] = $computopPayNowPaymentTransfer->getMac();
        $dataSubArray[ComputopApiConfig::TX_TYPE] = $computopPayNowPaymentTransfer->getTxType();
        $dataSubArray[ComputopApiConfig::ORDER_DESC] = $computopPayNowPaymentTransfer->getOrderDesc();
        $dataSubArray[ComputopApiConfig::ETI_ID] = $this->config->getEtiId();
        $dataSubArray[ComputopApiConfig::IP_ADDRESS] = $computopPayNowPaymentTransfer->getClientIp();
        $dataSubArray[ComputopApiConfig::SHIPPING_ZIP] = $computopPayNowPaymentTransfer->getShippingZip();

        $dataSubArray[ComputopApiConfig::MSG_VER] = ComputopApiConfig::PSD2_MSG_VERSION;
        $dataSubArray[ComputopApiConfig::BILL_TO_CUSTOMER] = $this->encodeRequestParameterData(
            $computopPayNowPaymentTransfer->getBillToCustomer()->toArray(true, true)
        );
        $dataSubArray[ComputopApiConfig::SHIP_TO_CUSTOMER] = $this->encodeRequestParameterData(
            $computopPayNowPaymentTransfer->getShipToCustomer()->toArray(true, true)
        );
        $dataSubArray[ComputopApiConfig::BILLING_ADDRESS] = $this->encodeRequestParameterData(
            $computopPayNowPaymentTransfer->getBillingAddress()->toArray(true, true)
        );
        $dataSubArray[ComputopApiConfig::SHIPPING_ADDRESS] = $this->encodeRequestParameterData(
            $computopPayNowPaymentTransfer->getShippingAddress()->toArray(true, true)
        );
        $dataSubArray[ComputopApiConfig::CREDENTIAL_ON_FILE] = $this->encodeRequestParameterData(
            $computopPayNowPaymentTransfer->getCredentialOnFile()->toArray(true, true)
        );
        $dataSubArray[ComputopApiConfig::BROWSER_INFO] = $this->encodeRequestParameterData(
            $computopPayNowPaymentTransfer->getBrowserInfo()->toArray(true, true)
        );

        return $dataSubArray;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer
     */
    protected function expandPayNowPaymentWithShipToCustomer(
        ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer,
        QuoteTransfer $quoteTransfer
    ): ComputopPayNowPaymentTransfer {
        $computopCustomerInfoTransfer = (new ComputopCustomerInfoTransfer())
            ->setConsumer(
                (new ComputopConsumerTransfer())
                    ->setFirstName($quoteTransfer->getShippingAddress()->getFirstName())
                    ->setLastName($quoteTransfer->getShippingAddress()->getLastName())
                    ->setSalutation($quoteTransfer->getShippingAddress()->getSalutation())
            )
            ->setEmail($quoteTransfer->getCustomer()->getEmail());

        $computopPayNowPaymentTransfer->setShipToCustomer($computopCustomerInfoTransfer);

        return $computopPayNowPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer
     */
    protected function expandPayNowPaymentWithBillToCustomer(
        ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer,
        QuoteTransfer $quoteTransfer
    ): ComputopPayNowPaymentTransfer {
        if ($quoteTransfer->getBillingSameAsShipping()) {
            $computopPayNowPaymentTransfer->setBillToCustomer(
                $computopPayNowPaymentTransfer->getShipToCustomer()
            );

            return $computopPayNowPaymentTransfer;
        }

        $computopCustomerInfoTransfer = (new ComputopCustomerInfoTransfer())
            ->setConsumer(
                (new ComputopConsumerTransfer())
                    ->setFirstName($quoteTransfer->getBillingAddress()->getFirstName())
                    ->setLastName($quoteTransfer->getBillingAddress()->getLastName())
                    ->setSalutation($quoteTransfer->getBillingAddress()->getSalutation())
            )
            ->setEmail($quoteTransfer->getCustomer()->getEmail());

        $computopPayNowPaymentTransfer->setBillToCustomer($computopCustomerInfoTransfer);

        return $computopPayNowPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer
     */
    protected function expandPayNowPaymentWithShippingAddress(
        ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer,
        QuoteTransfer $quoteTransfer
    ): ComputopPayNowPaymentTransfer {
        $countryCollectionTransfer = (new CountryCollectionTransfer())
            ->addCountries(
                (new CountryTransfer())->setIso2Code($quoteTransfer->getShippingAddress()->getIso2Code())
            );
        $countryCollectionTransfer = $this->countryClient->findCountriesByIso2Codes($countryCollectionTransfer);

        $computopAddressTransfer = (new ComputopAddressTransfer())
            ->setCity($quoteTransfer->getShippingAddress()->getCity())
            ->setCountry(
                (new ComputopCountryTransfer())
                    ->setCountryA3($countryCollectionTransfer->getCountries()->offsetGet(0)->getIso3Code())
            )
            ->setAddressLine1(
                (new ComputopAddressLineTransfer())
                    ->setStreet($quoteTransfer->getShippingAddress()->getAddress1())
                    ->setStreetNumber($quoteTransfer->getShippingAddress()->getAddress2())
            )
            ->setPostalCode($quoteTransfer->getShippingAddress()->getZipCode());

        $computopPayNowPaymentTransfer->setShippingAddress($computopAddressTransfer);

        return $computopPayNowPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer
     */
    protected function expandPayNowPaymentWithBillingAddress(
        ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer,
        QuoteTransfer $quoteTransfer
    ): ComputopPayNowPaymentTransfer {
        if ($quoteTransfer->getBillingSameAsShipping()) {
            $computopPayNowPaymentTransfer->setBillingAddress(
                $computopPayNowPaymentTransfer->getShippingAddress()
            );

            return $computopPayNowPaymentTransfer;
        }

        $countryCollectionTransfer = (new CountryCollectionTransfer())
            ->addCountries(
                (new CountryTransfer())->setIso2Code($quoteTransfer->getBillingAddress()->getIso2Code())
            );
        $countryCollectionTransfer = $this->countryClient->findCountriesByIso2Codes($countryCollectionTransfer);

        $computopAddressTransfer = (new ComputopAddressTransfer())
            ->setCity($quoteTransfer->getBillingAddress()->getCity())
            ->setCountry(
                (new ComputopCountryTransfer())
                    ->setCountryA3($countryCollectionTransfer->getCountries()->offsetGet(0)->getIso3Code())
            )
            ->setAddressLine1(
                (new ComputopAddressLineTransfer())
                    ->setStreet($quoteTransfer->getBillingAddress()->getAddress1())
                    ->setStreetNumber($quoteTransfer->getBillingAddress()->getAddress2())
            )
            ->setPostalCode($quoteTransfer->getBillingAddress()->getZipCode());

        $computopPayNowPaymentTransfer->setBillingAddress($computopAddressTransfer);

        return $computopPayNowPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer
     */
    protected function expandPayNowPaymentWithCredentialOnFIle(
        ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer
    ): ComputopPayNowPaymentTransfer {
        $computopCredentialOnFileTransfer = (new ComputopCredentialOnFileTransfer())
            ->setType(
                (new ComputopCredentialOnFileTypeTransfer())
                    ->setUnscheduled(ComputopApiConfig::UNSCHEDULED_CUSTOMER_INITIATED_TRANSACTION)
            )
            ->setInitialPayment(true);

        $computopPayNowPaymentTransfer->setCredentialOnFile($computopCredentialOnFileTransfer);

        return $computopPayNowPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer
     */
    protected function expandPayNowPaymentWithBrowserInfo(
        ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer
    ): ComputopPayNowPaymentTransfer {
        $computopBrowserInfoTransfer = (new ComputopBrowserInfoTransfer())
            ->setAcceptHeaders($this->request->headers->get(static::HEADER_ACCEPT))
            ->setIpAddress($this->request->getClientIp())
            ->setUserAgent($this->request->headers->get(static::HEADER_USER_AGENT))
            ->setJavaEnabled(false)
            ->setJavaScriptEnabled(true)
            ->setColorDepth(static::IFRAME_COLOR_DEPTH)
            ->setScreenHeight(static::IFRAME_SCREEN_HEIGHT)
            ->setScreenWidth(static::IFRAME_SCREEN_WIDTH)
            ->setTimeZoneOffset(static::IFRAME_TIME_ZONE_OFFSET)
            ->setLanguage(mb_strtoupper($this->store->getCurrentLanguage()));

        $computopPayNowPaymentTransfer->setBrowserInfo($computopBrowserInfoTransfer);

        return $computopPayNowPaymentTransfer;
    }
}
