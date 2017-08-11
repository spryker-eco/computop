<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopCreditCardOrderResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;

class OrderCreditCardConverter implements ConverterInterface
{

    const ORDER_METHOD = 'ORDER';

    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardOrderResponseTransfer
     */
    public function createResponseTransfer(array $decryptedArray, ComputopResponseHeaderTransfer $header)
    {
        $computopCreditCardResponseTransfer = new ComputopCreditCardOrderResponseTransfer();

        $computopCreditCardResponseTransfer->fromArray($decryptedArray, true);
        $header->setMethod(self::ORDER_METHOD);
        $computopCreditCardResponseTransfer->setHeader($header);

        return $computopCreditCardResponseTransfer;
    }

}
