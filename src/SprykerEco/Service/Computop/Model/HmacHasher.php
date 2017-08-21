<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model;

use Spryker\Shared\Config\Config;
use SprykerEco\Shared\Computop\ComputopConstants;

class HmacHasher implements HmacHasherInterface
{

    const SHA256 = 'sha256';

    /**
     * @inheritdoc
     */
    public function getEncryptedValue($value)
    {
        return strtoupper(
            hash_hmac(self::SHA256, $value, Config::get(ComputopConstants::COMPUTOP_HMAC_PASSWORD_KEY))
        );
    }

}