<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Spryker\Shared\Config\Config;
use SprykerEco\Shared\Computop\ComputopConstants;

class Hash implements HashInterface
{

    const SHA256 = 'sha256';

    const SEPARATOR = '*';

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getMacValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        $macOriginData = self::SEPARATOR .
            $cardPaymentTransfer->getTransId() . self::SEPARATOR .
            $cardPaymentTransfer->getMerchantId() . self::SEPARATOR .
            $cardPaymentTransfer->getAmount() . self::SEPARATOR .
            $cardPaymentTransfer->getCurrency() . self::SEPARATOR;

        return hash_hmac(self::SHA256, $macOriginData, Config::get(ComputopConstants::COMPUTOP_HASH_PASSWORD));
    }

}
