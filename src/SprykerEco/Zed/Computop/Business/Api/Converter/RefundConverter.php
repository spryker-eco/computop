<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Converter;

use Generated\Shared\Transfer\ComputopCreditCardRefundResponseTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

class RefundConverter extends AbstractConverter implements ConverterInterface
{

    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardRefundResponseTransfer
     */
    protected function getResponseTransfer($decryptedArray)
    {
        $computopCreditCardResponseTransfer = new ComputopCreditCardRefundResponseTransfer();
        $computopCreditCardResponseTransfer->fromArray($decryptedArray, true);

        //optional fields
        $computopCreditCardResponseTransfer->setAId(
            isset($decryptedDataArray[ComputopConstants::A_ID_F_N]) ? $decryptedDataArray[ComputopConstants::A_ID_F_N] : null
        );
        $computopCreditCardResponseTransfer->setTransactionId(
            isset($decryptedDataArray[ComputopConstants::TRANSACTION_ID_F_N]) ? $decryptedDataArray[ComputopConstants::TRANSACTION_ID_F_N] : null
        );
        $computopCreditCardResponseTransfer->setAmount(
            isset($decryptedDataArray[ComputopConstants::AMOUNT_F_N]) ? $decryptedDataArray[ComputopConstants::AMOUNT_F_N] : null
        );
        $computopCreditCardResponseTransfer->setCodeExt(
            isset($decryptedDataArray[ComputopConstants::CODE_EXT_F_N]) ? $decryptedDataArray[ComputopConstants::CODE_EXT_F_N] : null
        );
        $computopCreditCardResponseTransfer->setErrorText(
            isset($decryptedDataArray[ComputopConstants::ERROR_TEXT_F_N]) ? $decryptedDataArray[ComputopConstants::ERROR_TEXT_F_N] : null
        );
        $computopCreditCardResponseTransfer->setRefNr(
            isset($decryptedDataArray[ComputopConstants::REF_NR_F_N]) ? $decryptedDataArray[ComputopConstants::REF_NR_F_N] : null
        );
        $computopCreditCardResponseTransfer->setHeader(
            $this->computopService->extractHeader($decryptedArray)
        );

        return $computopCreditCardResponseTransfer;
    }

}
