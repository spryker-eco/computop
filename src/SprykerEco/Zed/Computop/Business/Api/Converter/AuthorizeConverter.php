<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Converter;

use Generated\Shared\Transfer\ComputopCreditCardAuthorizeResponseTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

class AuthorizeConverter extends AbstractConverter implements ConverterInterface
{

    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardAuthorizeResponseTransfer
     */
    protected function getResponseTransfer($decryptedArray)
    {
        $computopCreditCardResponseTransfer = new ComputopCreditCardAuthorizeResponseTransfer();

        $computopCreditCardResponseTransfer->fromArray($decryptedArray, true);

        $computopCreditCardResponseTransfer->setRefNr(
            isset($decryptedDataArray[ComputopConstants::REF_NR_F_N]) ? $decryptedDataArray[ComputopConstants::REF_NR_F_N] : null
        );

        $computopCreditCardResponseTransfer->setHeader(
            $this->computopService->extractHeader($decryptedArray)
        );

        return $computopCreditCardResponseTransfer;
    }

}
