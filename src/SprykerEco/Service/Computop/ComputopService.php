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
    public function computopMacEncode(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        return $this->getFactory()->createHash()->getMacValue($cardPaymentTransfer);
    }

    /**
     * @param string $plaintext
     * @param string $password
     *
     * @return string
     */
    public function computopBlowfishEncode($plaintext, $password)
    {
        return $this->getFactory()->createBlowfish()->fullBlowfishEncode($plaintext, $password);
    }

}
