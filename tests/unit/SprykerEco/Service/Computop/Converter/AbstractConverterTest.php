<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Service\Computop\Converter;

use SprykerEco\Service\Computop\Model\Converter\Computop;
use Unit\SprykerEco\Service\Computop\AbstractComputopTest;

abstract class AbstractConverterTest extends AbstractComputopTest
{

    /**
     * @return \SprykerEco\Service\Computop\Model\Converter\Computop
     */
    protected function createConverter()
    {
        return new Computop($this->createComputopConfigMock());
    }

}
