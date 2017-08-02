<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop;

use Spryker\Service\Kernel\AbstractServiceFactory;
use SprykerEco\Service\Computop\Model\Blowfish;
use SprykerEco\Service\Computop\Model\Converter\Computop as ComputopConverter;
use SprykerEco\Service\Computop\Model\Mapper\Computop as ComputopMapper;
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
     * @return \SprykerEco\Service\Computop\Model\Converter\Computop
     */
    public function createComputopConverter()
    {
        return new ComputopConverter();
    }

    /**
     * @return \SprykerEco\Service\Computop\Model\Mapper\Computop
     */
    public function createComputopMapper()
    {
        return new ComputopMapper();
    }

    /**
     * @return \SprykerEco\Service\Computop\Model\HashHmac
     */
    public function createHashHmac()
    {
        return new HashHmac();
    }

}
