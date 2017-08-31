<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model;

use Spryker\Service\Kernel\AbstractBundleConfig;

class HmacHasher implements HmacHasherInterface
{

    const HASH_TYPE = 'sha256';

    /**
     * @var \Spryker\Service\Kernel\AbstractBundleConfig
     */
    protected $config;

    /**
     * @param \Spryker\Service\Kernel\AbstractBundleConfig $config
     */
    public function __construct(AbstractBundleConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function getEncryptedValue($value)
    {
        return strtoupper(
            hash_hmac(self::HASH_TYPE, $value, $this->config->getHmacPassword())
        );
    }

}
