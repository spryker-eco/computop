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
use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\ComputopCustomerInfoTransfer;
use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Router\Router\Router;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin;

class CreditCardMapper extends AbstractMapper
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    public function createComputopPaymentTransfer(QuoteTransfer $quoteTransfer)
    {
        /** @var \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopPaymentTransfer */
        $computopPaymentTransfer = parent::createComputopPaymentTransfer($quoteTransfer);

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
                $this->config->getCreditCardInitActionUrl(),
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
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(QuoteTransfer $quoteTransfer)
    {
        $computopPaymentTransfer = new ComputopCreditCardPaymentTransfer();

        $computopPaymentTransfer->setCapture(
            $this->getCaptureType(ComputopSharedConfig::PAYMENT_METHOD_CREDIT_CARD)
        );
        $computopPaymentTransfer->setTransId($this->generateTransId($quoteTransfer));
        $computopPaymentTransfer->setTxType($this->config->getCreditCardTxType());
        $computopPaymentTransfer->setUrlSuccess(
            $this->router->generate(ComputopRouteProviderPlugin::CREDIT_CARD_SUCCESS, [], Router::ABSOLUTE_URL)
        );
        $computopPaymentTransfer->setOrderDesc(
            $this->computopApiService->getDescriptionValue($quoteTransfer->getItems()->getArrayCopy())
        );

        $computopPaymentTransfer = $this->expandCreditCardPaymentWithShipToCustomer($computopPaymentTransfer, $quoteTransfer);
        $computopPaymentTransfer = $this->expandCreditCardPaymentWithBillToCustomer($computopPaymentTransfer, $quoteTransfer);
        $computopPaymentTransfer = $this->expandCreditCardPaymentWithShippingAddress($computopPaymentTransfer, $quoteTransfer);
        $computopPaymentTransfer = $this->expandCreditCardPaymentWithBillingAddress($computopPaymentTransfer, $quoteTransfer);
        $computopPaymentTransfer = $this->expandCreditCardPaymentWithCredentialOnFIle($computopPaymentTransfer);

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
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
     * @param string $merchantId
     * @param string $data
     * @param int $length
     *
     * @return array
     */
    protected function getQueryParameters($merchantId, $data, $length)
    {
        $queryData = parent::getQueryParameters($merchantId, $data, $length);

        if ($this->config->getCreditCardTemplateEnabled()) {
            $queryData[ComputopApiConfig::TEMPLATE] = $merchantId;
        }

        return $queryData;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function expandCreditCardPaymentWithShipToCustomer(
        ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer,
        QuoteTransfer $quoteTransfer
    ): ComputopCreditCardPaymentTransfer {
        $computopCustomerInfoTransfer = (new ComputopCustomerInfoTransfer())
            ->setConsumer(
                (new ComputopConsumerTransfer())
                    ->setFirstName($quoteTransfer->getShippingAddress()->getFirstName())
                    ->setLastName($quoteTransfer->getShippingAddress()->getLastName())
                    ->setSalutation($quoteTransfer->getShippingAddress()->getSalutation())
            )
            ->setEmail($quoteTransfer->getCustomer()->getEmail());

        $computopCreditCardPaymentTransfer->setShipToCustomer($computopCustomerInfoTransfer);

        return $computopCreditCardPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function expandCreditCardPaymentWithBillToCustomer(
        ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer,
        QuoteTransfer $quoteTransfer
    ): ComputopCreditCardPaymentTransfer {
        if ($quoteTransfer->getBillingSameAsShipping()) {
            $computopCreditCardPaymentTransfer->setBillToCustomer(
                $computopCreditCardPaymentTransfer->getShipToCustomer()
            );

            return $computopCreditCardPaymentTransfer;
        }

        $computopCustomerInfoTransfer = (new ComputopCustomerInfoTransfer())
            ->setConsumer(
                (new ComputopConsumerTransfer())
                    ->setFirstName($quoteTransfer->getBillingAddress()->getFirstName())
                    ->setLastName($quoteTransfer->getBillingAddress()->getLastName())
                    ->setSalutation($quoteTransfer->getBillingAddress()->getSalutation())
            )
            ->setEmail($quoteTransfer->getCustomer()->getEmail());

        $computopCreditCardPaymentTransfer->setBillToCustomer($computopCustomerInfoTransfer);

        return $computopCreditCardPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function expandCreditCardPaymentWithShippingAddress(
        ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer,
        QuoteTransfer $quoteTransfer
    ): ComputopCreditCardPaymentTransfer {
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

        $computopCreditCardPaymentTransfer->setShippingAddress($computopAddressTransfer);

        return $computopCreditCardPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function expandCreditCardPaymentWithBillingAddress(
        ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer,
        QuoteTransfer $quoteTransfer
    ): ComputopCreditCardPaymentTransfer {
        if ($quoteTransfer->getBillingSameAsShipping()) {
            $computopCreditCardPaymentTransfer->setBillingAddress(
                $computopCreditCardPaymentTransfer->getShippingAddress()
            );

            return $computopCreditCardPaymentTransfer;
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

        $computopCreditCardPaymentTransfer->setBillingAddress($computopAddressTransfer);

        return $computopCreditCardPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function expandCreditCardPaymentWithCredentialOnFIle(
        ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer
    ): ComputopCreditCardPaymentTransfer {
        $computopCredentialOnFileTransfer = (new ComputopCredentialOnFileTransfer())
            ->setType(
                (new ComputopCredentialOnFileTypeTransfer())
                    ->setUnscheduled(ComputopApiConfig::UNSCHEDULED_CUSTOMER_INITIATED_TRANSACTION)
            )
            ->setInitialPayment(true);

        $computopCreditCardPaymentTransfer->setCredentialOnFile($computopCredentialOnFileTransfer);

        return $computopCreditCardPaymentTransfer;
    }
}
