<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopPaydirektOrderResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\ComputopFieldNameConstants;

class OrderPaydirektConverter extends AbstractOrderConverter
{
    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Generated\Shared\Transfer\ComputopPaydirektOrderResponseTransfer
     */
    public function createResponseTransfer(array $decryptedArray, ComputopResponseHeaderTransfer $header)
    {
        $responseTransfer = new ComputopPaydirektOrderResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($header);
        //optional fields
        //todo: update after set up account
        $responseTransfer->setRefNr($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::REF_NR));

        return $responseTransfer;
    }
}
