<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Converter;

use Generated\Shared\Transfer\ComputopReverseResponseTransfer;
use SprykerEco\Shared\Computop\Config\ComputopFieldNameConstants;

class ReverseConverter extends AbstractConverter implements ConverterInterface
{
    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopReverseResponseTransfer
     */
    protected function getResponseTransfer(array $decryptedArray)
    {
        $computopResponseTransfer = new ComputopReverseResponseTransfer();
        $computopResponseTransfer->setHeader(
            $this->computopService->extractHeader($decryptedArray, $this->config->getReverseMethodName())
        );
        //optional fields
        $computopResponseTransfer->setAId($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::A_ID));
        $computopResponseTransfer->setTransactionId($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::TRANSACTION_ID));
        $computopResponseTransfer->setCodeExt($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::CODE_EXT));
        $computopResponseTransfer->setErrorText($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::ERROR_TEXT));
        $computopResponseTransfer->setRefNr($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::REF_NR));

        return $computopResponseTransfer;
    }
}
