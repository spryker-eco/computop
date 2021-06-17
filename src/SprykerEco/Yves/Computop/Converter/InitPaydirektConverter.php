<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopPaydirektInitResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class InitPaydirektConverter extends AbstractInitConverter
{
    /**
     * @param array $responseParamsArray
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $header
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function createResponseTransfer(
        array $responseParamsArray,
        ComputopApiResponseHeaderTransfer $header
    ): TransferInterface {
        $responseTransfer = new ComputopPaydirektInitResponseTransfer();
        $responseTransfer->fromArray($responseParamsArray, true);
        $responseTransfer->setHeader($header);
        //optional fields
        $responseTransfer->setRefNr($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::REF_NR));
        $responseTransfer->setShoppingBasketCategory($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::SHOPPING_BASKET_CATEGORY));
        $responseTransfer->setShippingFirstName($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::SHIPPING_FIRST_NAME));
        $responseTransfer->setShippingLastName($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::SHIPPING_LAST_NAME));
        $responseTransfer->setShippingCompany($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::SHIPPING_COMPANY));
        $responseTransfer->setShippingAddressAddition($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::SHIPPING_ADDRESS_ADDITION));
        $responseTransfer->setShippingStreet($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::SHIPPING_STREET));
        $responseTransfer->setShippingStreetNumber($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::SHIPPING_STREET_NUMBER));
        $responseTransfer->setShippingZip($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::SHIPPING_ZIP));
        $responseTransfer->setShippingCity($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::SHIPPING_CITY));
        $responseTransfer->setShippingCountryCode($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::SHIPPING_COUNTRY_CODE));
        $responseTransfer->setShippingEmail($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::SHIPPING_EMAIL));
        $responseTransfer->setAgeAccepted($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::AGE_ACCEPTED));
        $responseTransfer->setCustomerTransactionId($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::CUSTOMER_TRANSACTION_ID));

        return $responseTransfer;
    }
}
