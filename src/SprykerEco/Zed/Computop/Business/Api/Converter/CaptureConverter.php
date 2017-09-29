<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Converter;

use Generated\Shared\Transfer\ComputopCaptureResponseTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Shared\Computop\ComputopFieldNameConstants;

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
        $computopResponseTransfer->setAId($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::A_ID));
        $computopResponseTransfer->setTransactionId($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::TRANSACTION_ID));
        $computopResponseTransfer->setAmount($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::AMOUNT));
        $computopResponseTransfer->setCodeExt($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::CODE_EXT));
        $computopResponseTransfer->setErrorText($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::ERROR_TEXT));
        $computopResponseTransfer->setRefNr($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::REF_NR));

        return $computopResponseTransfer;
    }

}
