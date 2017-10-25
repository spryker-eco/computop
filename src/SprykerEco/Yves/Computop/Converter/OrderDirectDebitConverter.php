<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopDirectDebitOrderResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\Config\ComputopFieldName;

class OrderDirectDebitConverter extends AbstractOrderConverter
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
        $responseTransfer->setBankAccountIban($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::I_B_A_N));
        $responseTransfer->setBankAccountBic($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::B_I_C));
        $responseTransfer->setAccountOwner($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::ACCOUNT_OWNER));
        $responseTransfer->setAccountBank($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::ACCOUNT_BANK));
        $responseTransfer->setMandateId($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::MANDATE_ID));
        $responseTransfer->setDateOfSignature($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::DATE_OF_SIGNATURE_ID));
        $responseTransfer->setBankAccountPban($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::P_B_A_N));
        $responseTransfer->setMdtSeqType($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::MDT_SEQ_TYPE));

        return $responseTransfer;
    }
}
