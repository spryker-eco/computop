<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopSofortOrderResponseTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

class OrderSofortConverter extends AbstractOrderConverter implements ConverterInterface
{

    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Generated\Shared\Transfer\ComputopSofortOrderResponseTransfer
     */
    public function createFormattedResponseTransfer(array $decryptedArray, ComputopResponseHeaderTransfer $header)
    {
        $responseTransfer = new ComputopSofortOrderResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($header);
        $responseTransfer->setAccountBank($this->getValue($decryptedArray, ComputopConstants::ACCOUNT_BANK_F_N));
        $responseTransfer->setAccountOwner($this->getValue($decryptedArray, ComputopConstants::ACCOUNT_OWNER_F_N));
        $responseTransfer->setBankAccountBic($this->getValue($decryptedArray, ComputopConstants::B_I_C_F_N));
        $responseTransfer->setBankAccountIban($this->getValue($decryptedArray, ComputopConstants::I_B_A_N_F_N));

        return $responseTransfer;
    }

}
