<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopDirectDebitInitResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class InitDirectDebitConverter extends AbstractInitConverter
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
        $responseTransfer = new ComputopDirectDebitInitResponseTransfer();
        $responseTransfer->fromArray($responseParamsArray, true);
        $responseTransfer->setHeader($header);
        //optional fields
        $responseTransfer->setBankAccountIban($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::BANK_ACCOUNT_IBAN));
        $responseTransfer->setBankAccountBic($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::BANK_ACCOUNT_BIC));
        $responseTransfer->setAccountOwner($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::ACCOUNT_OWNER));
        $responseTransfer->setAccountBank($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::ACCOUNT_BANK));
        $responseTransfer->setMandateId($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::MANDATE_ID));
        $responseTransfer->setDateOfSignature($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::DATE_OF_SIGNATURE_ID));
        $responseTransfer->setBankAccountPban($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::BANK_ACCOUNT_PBAN));
        $responseTransfer->setMdtSeqType($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::MDT_SEQ_TYPE));

        return $responseTransfer;
    }
}
