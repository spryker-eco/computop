<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopPayPalOrderResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

class OrderPayPalConverter implements ConverterInterface
{

    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalOrderResponseTransfer
     */
    public function createResponseTransfer(array $decryptedArray, ComputopResponseHeaderTransfer $header)
    {
        $computopResponseTransfer = new ComputopPayPalOrderResponseTransfer();
        $computopResponseTransfer->fromArray($decryptedArray, true);
        $computopResponseTransfer->setHeader($header);
        $computopResponseTransfer->setEmail($decryptedArray[ComputopConstants::EMAIL_F_N]);
        $computopResponseTransfer->setFirstName($decryptedArray[ComputopConstants::FIRST_NAME_F_N]);
        $computopResponseTransfer->setLastName($decryptedArray[ComputopConstants::LAST_NAME_F_N]);

        $computopResponseTransfer->setTransactionId(
            isset($decryptedArray[ComputopConstants::TRANSACTION_ID_F_N]) ? $decryptedArray[ComputopConstants::TRANSACTION_ID_F_N] : null
        );

        return $computopResponseTransfer;
    }

}
