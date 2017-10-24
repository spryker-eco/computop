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
     * Test mode enabled
     * It changes description for API calls
     */
    const COMPUTOP_TEST_MODE_ENABLED = false;

    /**
     * @param string $method
     *
     * @return bool
     */
    public function isMacRequired($method)
    {
        return in_array($method, $this->get(ComputopConstants::RESPONSE_MAC_REQUIRED));
    }

    /**
     * @return string
     */
    public function getHmacPassword()
    {
        return $this->get(ComputopConstants::HMAC_PASSWORD);
    }

    /**
     * @return bool
     */
    public function isTestMode()
    {
        return self::COMPUTOP_TEST_MODE_ENABLED;
    }
}
