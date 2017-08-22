<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Converter;

use Generated\Shared\Transfer\ComputopCreditCardCaptureResponseTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

class CaptureConverter extends AbstractConverter implements ConverterInterface
{

    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardCaptureResponseTransfer
     */
    protected function getResponseTransfer(array $decryptedArray)
    {
        $computopCreditCardResponseTransfer = new ComputopCreditCardCaptureResponseTransfer();

        $computopCreditCardResponseTransfer->fromArray($decryptedArray, true);

        //optional fields
        $computopCreditCardResponseTransfer->setAId(
            isset($decryptedArray[ComputopConstants::A_ID_F_N]) ? $decryptedArray[ComputopConstants::A_ID_F_N] : null
        );
        $computopCreditCardResponseTransfer->setTransactionId(
            isset($decryptedArray[ComputopConstants::TRANSACTION_ID_F_N]) ? $decryptedArray[ComputopConstants::TRANSACTION_ID_F_N] : null
        );
        $computopCreditCardResponseTransfer->setAmount(
            isset($decryptedArray[ComputopConstants::AMOUNT_F_N]) ? $decryptedArray[ComputopConstants::AMOUNT_F_N] : null
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

        $computopCreditCardResponseTransfer->setHeader(
            $this->computopService->extractHeader($decryptedArray, ComputopConstants::CAPTURE_METHOD)
        );

        return $computopCreditCardResponseTransfer;
    }

}
