<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop;

use Spryker\Service\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\Computop\ComputopConstants;

class ComputopConfig extends AbstractBundleConfig
{

    /**
     * @param string $method
     *
     * @return bool
     */
    public function isMacRequired($method)
    {
        return in_array($method, $this->get(ComputopConstants::COMPUTOP_RESPONSE_MAC_REQUIRED_KEY));
    }

    /**
     * @return string
     */
    public function getHmacPassword()
    {
        return $this->get(ComputopConstants::COMPUTOP_HMAC_PASSWORD_KEY);
    }

    /**
     * @return bool
     */
    public function isTestMode()
    {
        return $this->get(ComputopConstants::COMPUTOP_TEST_ENABLED_KEY);
    }

}