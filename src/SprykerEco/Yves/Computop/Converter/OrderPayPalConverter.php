<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopPayPalOrderResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

class OrderPayPalConverter extends AbstractOrderConverter implements ConverterInterface
{

    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalOrderResponseTransfer
     */
    public function createFormattedResponseTransfer(array $decryptedArray, ComputopResponseHeaderTransfer $header)
    {
        $responseTransfer = new ComputopPayPalOrderResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($header);
        $responseTransfer->setEmail($this->getValue($decryptedArray, ComputopConstants::EMAIL_F_N));
        $responseTransfer->setFirstName($this->getValue($decryptedArray, ComputopConstants::FIRST_NAME_F_N));
        $responseTransfer->setLastName($this->getValue($decryptedArray, ComputopConstants::LAST_NAME_F_N));
        //optional fields
        $responseTransfer->setTransactionId($this->getValue($decryptedArray, ComputopConstants::TRANSACTION_ID_F_N));

        return $responseTransfer;
    }

}
