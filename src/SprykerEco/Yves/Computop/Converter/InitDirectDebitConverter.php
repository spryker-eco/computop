<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopDirectDebitInitResponseTransfer;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class InitDirectDebitConverter extends AbstractInitConverter
{
    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $header
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createResponseTransfer(array $decryptedArray, ComputopApiResponseHeaderTransfer $header)
    {
        $responseTransfer = new ComputopDirectDebitInitResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($header);
        //optional fields
        $responseTransfer->setBankAccountIban($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::BANK_ACCOUNT_IBAN));
        $responseTransfer->setBankAccountBic($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::BANK_ACCOUNT_BIC));
        $responseTransfer->setAccountOwner($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::ACCOUNT_OWNER));
        $responseTransfer->setAccountBank($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::ACCOUNT_BANK));
        $responseTransfer->setMandateId($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::MANDATE_ID));
        $responseTransfer->setDateOfSignature($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::DATE_OF_SIGNATURE_ID));
        $responseTransfer->setBankAccountPban($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::BANK_ACCOUNT_PBAN));
        $responseTransfer->setMdtSeqType($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::MDT_SEQ_TYPE));

        return $responseTransfer;
    }
}
