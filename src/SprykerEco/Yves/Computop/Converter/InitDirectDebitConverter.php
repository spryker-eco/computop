<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopDirectDebitInitResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class InitDirectDebitConverter extends AbstractInitConverter
{
    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function createResponseTransfer(array $decryptedArray, ComputopResponseHeaderTransfer $header)
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
