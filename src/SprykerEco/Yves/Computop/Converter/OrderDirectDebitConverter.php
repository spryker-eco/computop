<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopDirectDebitOrderResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

class OrderDirectDebitConverter extends AbstractOrderConverter implements ConverterInterface
{

    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Generated\Shared\Transfer\ComputopDirectDebitOrderResponseTransfer
     */
    public function createFormattedResponseTransfer(array $decryptedArray, ComputopResponseHeaderTransfer $header)
    {
        $responseTransfer = new ComputopDirectDebitOrderResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($header);
        $responseTransfer->setBankAccountIban($this->getValue($decryptedArray, ComputopConstants::I_B_A_N_F_N));
        $responseTransfer->setMandateId($this->getValue($decryptedArray, ComputopConstants::MANDATE_ID_F_N));
        $responseTransfer->setDateOfSignature($this->getValue($decryptedArray, ComputopConstants::DATE_OF_SIGNATURE_ID_F_N));
        $responseTransfer->setMdtSeqType($this->getValue($decryptedArray, ComputopConstants::MDT_SEQ_TYPE_F_N));
        $responseTransfer->setAccountOwner($this->getValue($decryptedArray, ComputopConstants::ACCOUNT_OWNER_F_N));
        //optional fields
        $responseTransfer->setAccountBank($this->getValue($decryptedArray, ComputopConstants::ACCOUNT_BANK_F_N));
        $responseTransfer->setBankAccountPban($this->getValue($decryptedArray, ComputopConstants::P_B_A_N_F_N));
        $responseTransfer->setBankAccountBic($this->getValue($decryptedArray, ComputopConstants::B_I_C_F_N));

        return $responseTransfer;
    }

}
