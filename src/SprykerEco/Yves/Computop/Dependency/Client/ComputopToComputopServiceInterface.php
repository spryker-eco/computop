<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Dependency\Client;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;

interface ComputopToComputopServiceInterface
{

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function computopMacEncode(ComputopCreditCardPaymentTransfer $cardPaymentTransfer);

    /**
     * @param string $plaintext
     * @param string $password
     *
     * @return string
     */
    public function computopBlowfishEncode($plaintext, $password);

}
