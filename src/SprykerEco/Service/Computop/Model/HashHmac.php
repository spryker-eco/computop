<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model;

use Spryker\Shared\Config\Config;
use SprykerEco\Shared\Computop\ComputopConstants;

class HashHmac implements HashHmacInterface
{

    const SHA256 = 'sha256';

    /**
     * @param string $value
     *
     * @return string
     */
    public function hashHmacValue($value)
    {
        return hash_hmac(self::SHA256, $value, Config::get(ComputopConstants::COMPUTOP_HMAC_PASSWORD));
    }

}
