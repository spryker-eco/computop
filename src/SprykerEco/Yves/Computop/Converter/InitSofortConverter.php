<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopSofortInitResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class InitSofortConverter extends AbstractInitConverter
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
        $responseTransfer = new ComputopSofortInitResponseTransfer();
        $responseTransfer->fromArray($responseParamsArray, true);
        $responseTransfer->setHeader($header);
        $responseTransfer->setAccountBank($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::ACCOUNT_BANK));
        $responseTransfer->setAccountOwner($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::ACCOUNT_OWNER));
        $responseTransfer->setBankAccountBic($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::BANK_ACCOUNT_BIC));
        $responseTransfer->setBankAccountIban($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::BANK_ACCOUNT_IBAN));
        //optional fields
        $responseTransfer->setFirstName($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::FIRST_NAME));
        $responseTransfer->setLastName($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::LAST_NAME));
        $responseTransfer->setAddressStreet($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::ADDRESS_STREET));
        $responseTransfer->setAddressCity($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::ADDRESS_CITY));
        $responseTransfer->setAddressZip($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::ADDRESS_ZIP));
        $responseTransfer->setBirthday($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::BIRTHDAY));
        $responseTransfer->setAge($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::AGE));

        return $responseTransfer;
    }
}
