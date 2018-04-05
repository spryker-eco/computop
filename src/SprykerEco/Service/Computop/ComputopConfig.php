<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop;

use Spryker\Service\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\Computop\ComputopConstants;

class ComputopConfig extends AbstractBundleConfig implements ComputopConfigInterface
{
    /**
     * @param string $method
     *
     * @return bool
     */
    public function isMacRequired($method)
    {
        return in_array($method, (array)$this->get(ComputopConstants::RESPONSE_MAC_REQUIRED, []));
    }

    /**
     * @return string
     */
    public function getHmacPassword()
    {
        return $this->get(ComputopConstants::HMAC_PASSWORD);
    }
}
