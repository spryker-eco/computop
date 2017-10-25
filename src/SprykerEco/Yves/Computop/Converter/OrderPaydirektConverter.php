<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopPaydirektOrderResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\Config\ComputopFieldName;

class OrderPaydirektConverter extends AbstractOrderConverter
{
    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Generated\Shared\Transfer\ComputopPaydirektOrderResponseTransfer
     */
    public function createResponseTransfer(array $decryptedArray, ComputopResponseHeaderTransfer $header)
    {
        $responseTransfer = new ComputopPaydirektOrderResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($header);
        //optional fields
        $responseTransfer->setRefNr($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::REF_NR));
        $responseTransfer->setShoppingBasketCategory($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::SHOPPING_BASKET_CATEGORY));
        $responseTransfer->setShippingFirstName($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::SHIPPING_FIRST_NAME));
        $responseTransfer->setShippingLastName($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::SHIPPING_LAST_NAME));
        $responseTransfer->setShippingCompany($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::SHIPPING_COMPANY));
        $responseTransfer->setShippingAddressAddition($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::SHIPPING_ADDRESS_ADDITION));
        $responseTransfer->setShippingStreet($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::SHIPPING_STREET));
        $responseTransfer->setShippingStreetNumber($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::SHIPPING_STREET_NUMBER));
        $responseTransfer->setShippingZip($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::SHIPPING_ZIP));
        $responseTransfer->setShippingCity($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::SHIPPING_CITY));
        $responseTransfer->setShippingCountryCode($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::SHIPPING_COUNTRY_CODE));
        $responseTransfer->setShippingEmail($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::SHIPPING_EMAIL));
        $responseTransfer->setAgeAccepted($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::AGE_ACCEPTED));

        return $responseTransfer;
    }
}
