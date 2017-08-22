<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model;

use SprykerEco\Service\Computop\ComputopConfig;

class HmacHasher implements HmacHasherInterface
{

    const HASH_TYPE = 'sha256';

    /**
     * @var \SprykerEco\Service\Computop\ComputopConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Service\Computop\ComputopConfig $config
     */
    public function __construct(ComputopConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function getEncryptedValue($value)
    {
        return strtoupper(
            hash_hmac(self::HASH_TYPE, $value, $this->config->getHmacPassword())
        );
    }

}
