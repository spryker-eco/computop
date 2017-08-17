<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Converter;

use Generated\Shared\Transfer\ComputopCreditCardReverseResponseTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

class ReverseConverter extends AbstractConverter implements ConverterInterface
{

    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardReverseResponseTransfer
     */
    protected function getResponseTransfer($decryptedArray)
    {
        $computopCreditCardResponseTransfer = new ComputopCreditCardReverseResponseTransfer();
        $computopCreditCardResponseTransfer->setHeader(
            $this->computopService->extractHeader($decryptedArray, ComputopConstants::REVERSE_METHOD)
        );

        //optional fields
        $computopCreditCardResponseTransfer->setAId(
            isset($decryptedArray[ComputopConstants::A_ID_F_N]) ? $decryptedArray[ComputopConstants::A_ID_F_N] : null
        );
        $computopCreditCardResponseTransfer->setTransactionId(
            isset($decryptedArray[ComputopConstants::TRANSACTION_ID_F_N]) ? $decryptedArray[ComputopConstants::TRANSACTION_ID_F_N] : null
        );
        $computopCreditCardResponseTransfer->setCodeExt(
            isset($decryptedArray[ComputopConstants::CODE_EXT_F_N]) ? $decryptedArray[ComputopConstants::CODE_EXT_F_N] : null
        );
        $computopCreditCardResponseTransfer->setErrorText(
            isset($decryptedArray[ComputopConstants::ERROR_TEXT_F_N]) ? $decryptedArray[ComputopConstants::ERROR_TEXT_F_N] : null
        );
        $computopCreditCardResponseTransfer->setRefNr(
            isset($decryptedArray[ComputopConstants::REF_NR_F_N]) ? $decryptedArray[ComputopConstants::REF_NR_F_N] : null
        );

        return $computopCreditCardResponseTransfer;
    }

}
