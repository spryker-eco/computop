<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \SprykerEco\Service\Computop\ComputopServiceFactory getFactory()
 */
class ComputopService extends AbstractService implements ComputopServiceInterface
{

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function computopMacHashHmacValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        return $this->hashHmacValue($this->getFactory()->createComputop()->getMacValue($cardPaymentTransfer));
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function hashHmacValue($value)
    {
        return $this->getFactory()->createHashHmac()->hashHmacValue($value);
    }

    /**
     * @param string $plaintext
     * @param int $len
     * @param string $password
     *
     * @return string
     */
    public function blowfishEncryptedValue($plaintext, $len, $password)
    {
        return $this->getFactory()->createBlowfish()->blowfishEncryptedValue($plaintext, $len, $password);
    }

    /**
     * @param string $cipher
     * @param int $len
     * @param string $password
     *
     * @return string
     */
    public function blowfishDecryptedValue($cipher, $len, $password)
    {
        return $this->getFactory()->createBlowfish()->blowfishDecryptedValue($cipher, $len, $password);
    }

}
