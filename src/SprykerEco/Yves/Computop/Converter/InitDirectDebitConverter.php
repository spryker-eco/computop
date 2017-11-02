<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopDirectDebitOrderResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class InitDirectDebitConverter extends AbstractInitConverter
{
    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Generated\Shared\Transfer\ComputopDirectDebitOrderResponseTransfer
     */
    public function createResponseTransfer(array $decryptedArray, ComputopResponseHeaderTransfer $header)
    {
        $responseTransfer = new ComputopDirectDebitOrderResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($header);
        //optional fields
        $responseTransfer->setBankAccountIban($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::BANK_ACCOUNT_IBAN));
        $responseTransfer->setBankAccountBic($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::BANK_ACCOUNT_BIC));
        $responseTransfer->setAccountOwner($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::ACCOUNT_OWNER));
        $responseTransfer->setAccountBank($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::ACCOUNT_BANK));
        $responseTransfer->setMandateId($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::MANDATE_ID));
        $responseTransfer->setDateOfSignature($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::DATE_OF_SIGNATURE_ID));
        $responseTransfer->setBankAccountPban($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::BANK_ACCOUNT_PBAN));
        $responseTransfer->setMdtSeqType($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::MDT_SEQ_TYPE));

        return $responseTransfer;
    }
}
