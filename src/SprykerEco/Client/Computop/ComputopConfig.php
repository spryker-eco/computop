<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Computop;

use Spryker\Client\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\Computop\ComputopConstants;

class ComputopConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function isCrifEnabled(): bool
    {
        return (bool)$this->get(ComputopConstants::CRIF_ENABLED);
    }
}
