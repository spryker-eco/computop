<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Service\Computop\Converter;

use Codeception\TestCase\Test;
use SprykerEco\Service\Computop\Model\Converter\Computop;

abstract class AbstractConverterTest extends Test
{

    /**
     * @return \SprykerEco\Service\Computop\Model\Converter\Computop
     */
    protected function createConverter()
    {
        return new Computop();
    }

}
