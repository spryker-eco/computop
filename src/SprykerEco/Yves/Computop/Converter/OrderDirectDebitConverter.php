<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopDirectDebitOrderResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

class OrderDirectDebitConverter implements ConverterInterface
{

    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Generated\Shared\Transfer\ComputopDirectDebitOrderResponseTransfer
     */
    public function createResponseTransfer(array $decryptedArray, ComputopResponseHeaderTransfer $header)
    {
        $computopResponseTransfer = new ComputopDirectDebitOrderResponseTransfer();

        $computopResponseTransfer->fromArray($decryptedArray, true);
        $computopResponseTransfer->setIban($decryptedArray[ComputopConstants::I_B_A_N_F_N]);
        $computopResponseTransfer->setMandateId($decryptedArray[ComputopConstants::MANDATE_ID_F_N]);
        $computopResponseTransfer->setDtOfSgntr($decryptedArray[ComputopConstants::DATE_OF_SIGNATURE_ID_F_N]);
        $computopResponseTransfer->setMdtSeqType($decryptedArray[ComputopConstants::MDT_SEQ_TYPE_F_N]);

        $computopResponseTransfer->setHeader($header);

        return $computopResponseTransfer;
    }

}
