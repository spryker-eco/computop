<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop;

use Spryker\Service\Kernel\AbstractServiceFactory;
use SprykerEco\Service\Computop\Model\Blowfish;
use SprykerEco\Service\Computop\Model\Computop;
use SprykerEco\Service\Computop\Model\HashHmac;

class ComputopServiceFactory extends AbstractServiceFactory
{

    /**
     * @return \SprykerEco\Service\Computop\Model\Blowfish
     */
    public function createBlowfish()
    {
        return new Blowfish();
    }

    /**
     * @return \SprykerEco\Service\Computop\Model\Computop
     */
    public function createComputop()
    {
        return new Computop();
    }

    /**
     * @return \SprykerEco\Service\Computop\Model\HashHmac
     */
    public function createHashHmac()
    {
        return new HashHmac();
    }

}
