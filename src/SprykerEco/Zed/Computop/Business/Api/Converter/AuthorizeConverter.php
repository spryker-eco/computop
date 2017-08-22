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
    protected function getResponseTransfer(array $decryptedArray)
    {
        $computopCreditCardResponseTransfer = new ComputopCreditCardAuthorizeResponseTransfer();

        $computopCreditCardResponseTransfer->fromArray($decryptedArray, true);

        $computopCreditCardResponseTransfer->setRefNr(
            isset($decryptedArray[ComputopConstants::REF_NR_F_N]) ? $decryptedArray[ComputopConstants::REF_NR_F_N] : null
        );

        $computopCreditCardResponseTransfer->setHeader(
            $this->computopService->extractHeader($decryptedArray, ComputopConstants::AUTHORIZE_METHOD)
        );

        return $computopCreditCardResponseTransfer;
    }

}
