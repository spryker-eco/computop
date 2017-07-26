<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;

interface ComputopServiceInterface
{

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function computopMacHashHmacValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer);

    /**
     * @param string $value
     *
     * @return string
     */
    public function hashHmacValue($value);

    /**
     * @param string $plaintext
     * @param string $password
     *
     * @return string
     */
    public function blowfishEncryptedValue($plaintext, $password);

}
