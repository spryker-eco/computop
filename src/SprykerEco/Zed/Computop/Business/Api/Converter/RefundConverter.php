<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Converter;

use Generated\Shared\Transfer\ComputopRefundResponseTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

class RefundConverter extends AbstractConverter implements ConverterInterface
{

    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopRefundResponseTransfer
     */
    protected function getResponseTransfer(array $decryptedArray)
    {
        $computopResponseTransfer = new ComputopRefundResponseTransfer();
        $computopResponseTransfer->fromArray($decryptedArray, true);

        //optional fields
        $computopResponseTransfer->setAId(
            isset($decryptedArray[ComputopConstants::A_ID_F_N]) ? $decryptedArray[ComputopConstants::A_ID_F_N] : null
        );
        $computopResponseTransfer->setTransactionId(
            isset($decryptedArray[ComputopConstants::TRANSACTION_ID_F_N]) ? $decryptedArray[ComputopConstants::TRANSACTION_ID_F_N] : null
        );
        $computopResponseTransfer->setAmount(
            isset($decryptedArray[ComputopConstants::AMOUNT_F_N]) ? $decryptedArray[ComputopConstants::AMOUNT_F_N] : null
        );
        $computopResponseTransfer->setCodeExt(
            isset($decryptedArray[ComputopConstants::CODE_EXT_F_N]) ? $decryptedArray[ComputopConstants::CODE_EXT_F_N] : null
        );
        $computopResponseTransfer->setErrorText(
            isset($decryptedArray[ComputopConstants::ERROR_TEXT_F_N]) ? $decryptedArray[ComputopConstants::ERROR_TEXT_F_N] : null
        );
        $computopResponseTransfer->setRefNr(
            isset($decryptedArray[ComputopConstants::REF_NR_F_N]) ? $decryptedArray[ComputopConstants::REF_NR_F_N] : null
        );
        $computopResponseTransfer->setHeader(
            $this->computopService->extractHeader($decryptedArray, ComputopConstants::REFUND_METHOD)
        );

        return $computopResponseTransfer;
    }

}
