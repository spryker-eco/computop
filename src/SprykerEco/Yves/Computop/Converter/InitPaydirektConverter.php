<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopPaydirektInitResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class InitPaydirektConverter extends AbstractInitConverter
{
    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function createResponseTransfer(array $decryptedArray, ComputopResponseHeaderTransfer $header)
    {
        $responseTransfer = new ComputopPaydirektInitResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($header);
        //optional fields
        $responseTransfer->setRefNr($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::REF_NR));
        $responseTransfer->setShoppingBasketCategory($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::SHOPPING_BASKET_CATEGORY));
        $responseTransfer->setShippingFirstName($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::SHIPPING_FIRST_NAME));
        $responseTransfer->setShippingLastName($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::SHIPPING_LAST_NAME));
        $responseTransfer->setShippingCompany($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::SHIPPING_COMPANY));
        $responseTransfer->setShippingAddressAddition($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::SHIPPING_ADDRESS_ADDITION));
        $responseTransfer->setShippingStreet($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::SHIPPING_STREET));
        $responseTransfer->setShippingStreetNumber($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::SHIPPING_STREET_NUMBER));
        $responseTransfer->setShippingZip($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::SHIPPING_ZIP));
        $responseTransfer->setShippingCity($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::SHIPPING_CITY));
        $responseTransfer->setShippingCountryCode($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::SHIPPING_COUNTRY_CODE));
        $responseTransfer->setShippingEmail($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::SHIPPING_EMAIL));
        $responseTransfer->setAgeAccepted($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::AGE_ACCEPTED));
        $responseTransfer->setCustomerTransactionId($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::CUSTOMER_TRANSACTION_ID));

        return $responseTransfer;
    }
}
