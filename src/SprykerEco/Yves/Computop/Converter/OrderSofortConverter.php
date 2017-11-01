<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopSofortOrderResponseTransfer;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class OrderSofortConverter extends AbstractOrderConverter
{
    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Generated\Shared\Transfer\ComputopSofortOrderResponseTransfer
     */
    public function createResponseTransfer(array $decryptedArray, ComputopResponseHeaderTransfer $header)
    {
        $responseTransfer = new ComputopSofortOrderResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($header);
        $responseTransfer->setAccountBank($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::ACCOUNT_BANK));
        $responseTransfer->setAccountOwner($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::ACCOUNT_OWNER));
        $responseTransfer->setBankAccountBic($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::BANK_ACCOUNT_BIC));
        $responseTransfer->setBankAccountIban($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::BANK_ACCOUNT_IBAN));
        //optional fields
        $responseTransfer->setFirstName($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::FIRST_NAME));
        $responseTransfer->setLastName($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::LAST_NAME));
        $responseTransfer->setAddressStreet($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::ADDRESS_STREET));
        $responseTransfer->setAddressCity($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::ADDRESS_CITY));
        $responseTransfer->setAddressZip($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::ADDRESS_ZIP));
        $responseTransfer->setBirthday($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::BIRTHDAY));
        $responseTransfer->setAge($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::AGE));

        return $responseTransfer;
    }
}
