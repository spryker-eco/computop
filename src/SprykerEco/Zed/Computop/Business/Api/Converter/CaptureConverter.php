<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Converter;

use Generated\Shared\Transfer\ComputopCaptureResponseTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

class CaptureConverter extends AbstractConverter implements ConverterInterface
{

    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopCaptureResponseTransfer
     */
    protected function getResponseTransfer(array $decryptedArray)
    {
        $computopResponseTransfer = new ComputopCaptureResponseTransfer();
        $computopResponseTransfer->fromArray($decryptedArray, true);
        $computopResponseTransfer->setHeader(
            $this->computopService->extractHeader($decryptedArray, ComputopConstants::CAPTURE_METHOD)
        );
        //optional fields
        $computopResponseTransfer->setAId($this->computopService->getResponseValue($decryptedArray, ComputopConstants::A_ID_F_N));
        $computopResponseTransfer->setTransactionId($this->computopService->getResponseValue($decryptedArray, ComputopConstants::TRANSACTION_ID_F_N));
        $computopResponseTransfer->setAmount($this->computopService->getResponseValue($decryptedArray, ComputopConstants::AMOUNT_F_N));
        $computopResponseTransfer->setCodeExt($this->computopService->getResponseValue($decryptedArray, ComputopConstants::CODE_EXT_F_N));
        $computopResponseTransfer->setErrorText($this->computopService->getResponseValue($decryptedArray, ComputopConstants::ERROR_TEXT_F_N));
        $computopResponseTransfer->setRefNr($this->computopService->getResponseValue($decryptedArray, ComputopConstants::REF_NR_F_N));

        return $computopResponseTransfer;
    }

}
