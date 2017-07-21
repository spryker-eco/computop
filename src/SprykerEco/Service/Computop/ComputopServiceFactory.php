<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop;

use Spryker\Service\Kernel\AbstractServiceFactory;
use SprykerEco\Service\Computop\Model\Hash;

class ComputopServiceFactory extends AbstractServiceFactory
{

    /**
     * @return \SprykerEco\Service\Computop\Model\Hash
     */
    public function createHash()
    {
        return new Hash();
    }

}
