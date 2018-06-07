<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilText\UtilTextService;
use SprykerEco\Service\Computop\Model\BlowfishHasher;
use SprykerEco\Service\Computop\Model\Converter\ComputopConverter;
use SprykerEco\Service\Computop\Model\HmacHasher;
use SprykerEco\Service\Computop\Model\Mapper\ComputopMapper;

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
     * @return \SprykerEco\Service\Computop\Model\Converter\ComputopConverterInterface
     */
    public function createComputopConverter()
    {
        return new ComputopConverter($this->getConfig());
    }

    /**
     * @return \SprykerEco\Service\Computop\Model\Mapper\ComputopMapperInterface
     */
    public function createComputopMapper()
    {
        return new ComputopMapper(
            $this->getConfig(),
            new UtilTextService()
        );
    }

    /**
     * @return \SprykerEco\Service\Computop\Model\HmacHasherInterface
     */
    public function createHmacHasher()
    {
        return new HmacHasher($this->getConfig());
    }
}
