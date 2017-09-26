<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopCreditCardOrderResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;

class OrderCreditCardConverter extends AbstractOrderConverter implements ConverterInterface
{

    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardOrderResponseTransfer
     */
    public function createFormattedResponseTransfer(array $decryptedArray, ComputopResponseHeaderTransfer $header)
    {
        $responseTransfer = new ComputopCreditCardOrderResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($header);

        return $responseTransfer;
    }

}
