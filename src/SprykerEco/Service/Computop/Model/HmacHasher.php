<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model;

use SprykerEco\Service\Computop\ComputopConfig;

class HmacHasher implements HmacHasherInterface
{

    const SHA256 = 'sha256';

    /**
     * @var \SprykerEco\Service\Computop\ComputopConfig
     */
    protected $config;

    /**
     * Computop constructor.
     *
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
            hash_hmac(self::SHA256, $value, $this->config->getHmacPassword())
        );
    }

}
