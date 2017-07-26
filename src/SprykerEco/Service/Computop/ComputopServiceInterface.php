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
    public function computopMacEncode(ComputopCreditCardPaymentTransfer $cardPaymentTransfer);

    /**
     * @param $plaintext
     * @param $password
     *
     * @return string
     */
    public function computopBlowfishEncode($plaintext, $password);

}
