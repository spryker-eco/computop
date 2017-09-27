<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopDirectDebitOrderResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

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
        $responseTransfer->setBankAccountIban($this->computopService->getResponseValue($decryptedArray, ComputopConstants::I_B_A_N_F_N));
        $responseTransfer->setBankAccountBic($this->computopService->getResponseValue($decryptedArray, ComputopConstants::B_I_C_F_N));
        $responseTransfer->setAccountOwner($this->computopService->getResponseValue($decryptedArray, ComputopConstants::ACCOUNT_OWNER_F_N));
        $responseTransfer->setAccountBank($this->computopService->getResponseValue($decryptedArray, ComputopConstants::ACCOUNT_BANK_F_N));
        $responseTransfer->setMandateId($this->computopService->getResponseValue($decryptedArray, ComputopConstants::MANDATE_ID_F_N));
        $responseTransfer->setDateOfSignature($this->computopService->getResponseValue($decryptedArray, ComputopConstants::DATE_OF_SIGNATURE_ID_F_N));
        $responseTransfer->setBankAccountPban($this->computopService->getResponseValue($decryptedArray, ComputopConstants::P_B_A_N_F_N));
        $responseTransfer->setMdtSeqType($this->computopService->getResponseValue($decryptedArray, ComputopConstants::MDT_SEQ_TYPE_F_N));

        return $responseTransfer;
    }

}
