<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Converter;

use Generated\Shared\Transfer\ComputopAuthorizeResponseTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

class AuthorizeConverter extends AbstractConverter implements ConverterInterface
{

    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopAuthorizeResponseTransfer
     */
    protected function getResponseTransfer(array $decryptedArray)
    {
        $computopResponseTransfer = new ComputopAuthorizeResponseTransfer();
        $computopResponseTransfer->fromArray($decryptedArray, true);
        $computopResponseTransfer->setHeader(
            $this->computopService->extractHeader($decryptedArray, ComputopConstants::AUTHORIZE_METHOD)
        );
        //optional field
        $computopResponseTransfer->setRefNr($this->computopService->getResponseValue($decryptedArray, ComputopConstants::REF_NR_F_N));

        return $computopResponseTransfer;
    }

}
