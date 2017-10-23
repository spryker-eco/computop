<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop;

use Spryker\Service\Kernel\AbstractServiceFactory;
use SprykerEco\Service\Computop\Model\BlowfishHasher;
use SprykerEco\Service\Computop\Model\Converter\Computop as ComputopConverter;
use SprykerEco\Service\Computop\Model\HmacHasher;
use SprykerEco\Service\Computop\Model\Mapper\Computop as ComputopMapper;

class ComputopServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \SprykerEco\Service\Computop\Model\BlowfishHasherInterface
     */
    public function createBlowfishHasher()
    {
        return new BlowfishHasher();
    }

    /**
     * @return \SprykerEco\Service\Computop\Model\Converter\ComputopInterface
     */
    public function createComputopConverter()
    {
        return new ComputopConverter($this->getConfig());
    }

    /**
     * @return \SprykerEco\Service\Computop\Model\Mapper\ComputopInterface
     */
    public function createComputopMapper()
    {
        return new ComputopMapper($this->getConfig());
    }

    /**
     * @return \SprykerEco\Service\Computop\Model\HmacHasherInterface
     */
    public function createHmacHasher()
    {
        return new HmacHasher($this->getConfig());
    }
}
