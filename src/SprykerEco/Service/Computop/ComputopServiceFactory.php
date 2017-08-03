<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop;

use Spryker\Service\Kernel\AbstractServiceFactory;
use SprykerEco\Service\Computop\Model\Blowfish;
use SprykerEco\Service\Computop\Model\Converter\Computop as ComputopConverter;
use SprykerEco\Service\Computop\Model\HashHmac;
use SprykerEco\Service\Computop\Model\Mapper\Computop as ComputopMapper;

class ComputopServiceFactory extends AbstractServiceFactory
{

    /**
     * @return \SprykerEco\Service\Computop\Model\BlowfishInterface
     */
    public function createBlowfish()
    {
        return new Blowfish();
    }

    /**
     * @return \SprykerEco\Service\Computop\Model\Converter\ComputopInterface
     */
    public function createComputopConverter()
    {
        return new ComputopConverter();
    }

    /**
     * @return \SprykerEco\Service\Computop\Model\Mapper\ComputopInterface
     */
    public function createComputopMapper()
    {
        return new ComputopMapper();
    }

    /**
     * @return \SprykerEco\Service\Computop\Model\HashHmacInterface
     */
    public function createHashHmac()
    {
        return new HashHmac();
    }

}
