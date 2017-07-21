<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \SprykerEco\Computop\Service\ComputopServiceFactory getFactory()
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

}
