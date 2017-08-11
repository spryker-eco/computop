<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\Computop\ComputopConstants;

class ComputopConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->get(ComputopConstants::COMPUTOP_MERCHANT_ID_KEY);
    }

    /**
     * @return string
     */
    public function getBlowfishPass()
    {
        return $this->get(ComputopConstants::COMPUTOP_BLOWFISH_PASSWORD_KEY);
    }

    /**
     * @return string
     */
    public function getHmacPass()
    {
        return $this->get(ComputopConstants::COMPUTOP_HMAC_PASSWORD_KEY);
    }

}
