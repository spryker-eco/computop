<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Generated\Shared\Transfer\ComputopAddressLineTransfer;
use Generated\Shared\Transfer\ComputopAddressTransfer;
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
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer
     */
    public function createComputopPaymentTransfer(QuoteTransfer $quoteTransfer)
    {
        /** @var \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $computopPaymentTransfer */
        $computopPaymentTransfer = parent::createComputopPaymentTransfer($quoteTransfer);

        $computopPaymentTransfer->setUrlNotify(
            $this->router->generate(ComputopRouteProviderPlugin::NOTIFY_PATH_NAME, [], Router::ABSOLUTE_URL)
        );
        $computopPaymentTransfer->setMac(
            $this->computopApiService->generateEncryptedMac(
                $this->createRequestTransfer($computopPaymentTransfer)
            )
        );

        $computopPaymentTransfer->setUrl(
            $this->config->getPayNowInitActionUrl()
        );

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

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $cardPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(ComputopPayNowPaymentTransfer $cardPaymentTransfer)
    {
        $dataSubArray[ComputopApiConfig::TRANS_ID] = $cardPaymentTransfer->getTransId();
        $dataSubArray[ComputopApiConfig::AMOUNT] = $cardPaymentTransfer->getAmount();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $cardPaymentTransfer->getCurrency();
        $dataSubArray[ComputopApiConfig::URL_SUCCESS] = $cardPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopApiConfig::URL_FAILURE] = $cardPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopApiConfig::CAPTURE] = $cardPaymentTransfer->getCapture();
        $dataSubArray[ComputopApiConfig::RESPONSE] = $cardPaymentTransfer->getResponse();
        $dataSubArray[ComputopApiConfig::MAC] = $cardPaymentTransfer->getMac();
        $dataSubArray[ComputopApiConfig::TX_TYPE] = $cardPaymentTransfer->getTxType();
        $dataSubArray[ComputopApiConfig::ORDER_DESC] = $cardPaymentTransfer->getOrderDesc();
        $dataSubArray[ComputopApiConfig::ETI_ID] = $this->config->getEtiId();
        $dataSubArray[ComputopApiConfig::IP_ADDRESS] = $cardPaymentTransfer->getClientIp();
        $dataSubArray[ComputopApiConfig::SHIPPING_ZIP] = $cardPaymentTransfer->getShippingZip();

        $dataSubArray[ComputopApiConfig::MSG_VER] = ComputopApiConfig::PSD2_MSG_VERSION;
        $dataSubArray[ComputopApiConfig::BILL_TO_CUSTOMER] = $this->encodeRequestParameterData(
            $cardPaymentTransfer->getBillToCustomer()->toArray(true, true)
        );
        $dataSubArray[ComputopApiConfig::SHIP_TO_CUSTOMER] = $this->encodeRequestParameterData(
            $cardPaymentTransfer->getShipToCustomer()->toArray(true, true)
        );
        $dataSubArray[ComputopApiConfig::BILLING_ADDRESS] = $this->encodeRequestParameterData(
            $cardPaymentTransfer->getBillingAddress()->toArray(true, true)
        );
        $dataSubArray[ComputopApiConfig::SHIPPING_ADDRESS] = $this->encodeRequestParameterData(
            $cardPaymentTransfer->getShippingAddress()->toArray(true, true)
        );
        $dataSubArray[ComputopApiConfig::CREDENTIAL_ON_FILE] = $this->encodeRequestParameterData(
            $cardPaymentTransfer->getCredentialOnFile()->toArray(true, true)
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
            ->setInitialPayment(false);

        $computopPayNowPaymentTransfer->setCredentialOnFile($computopCredentialOnFileTransfer);

        return $computopPayNowPaymentTransfer;
    }
}
