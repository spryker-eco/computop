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

        return $computopCreditCardResponseTransfer;
    }

}
