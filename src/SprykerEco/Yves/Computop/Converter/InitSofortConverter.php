<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopSofortInitResponseTransfer;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class InitSofortConverter extends AbstractInitConverter
{
    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $header
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createResponseTransfer(array $decryptedArray, ComputopApiResponseHeaderTransfer $header)
    {
        $responseTransfer = new ComputopSofortInitResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($header);
        $responseTransfer->setAccountBank($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::ACCOUNT_BANK));
        $responseTransfer->setAccountOwner($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::ACCOUNT_OWNER));
        $responseTransfer->setBankAccountBic($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::BANK_ACCOUNT_BIC));
        $responseTransfer->setBankAccountIban($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::BANK_ACCOUNT_IBAN));
        //optional fields
        $responseTransfer->setFirstName($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::FIRST_NAME));
        $responseTransfer->setLastName($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::LAST_NAME));
        $responseTransfer->setAddressStreet($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::ADDRESS_STREET));
        $responseTransfer->setAddressCity($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::ADDRESS_CITY));
        $responseTransfer->setAddressZip($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::ADDRESS_ZIP));
        $responseTransfer->setBirthday($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::BIRTHDAY));
        $responseTransfer->setAge($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::AGE));

        return $responseTransfer;
    }
}
