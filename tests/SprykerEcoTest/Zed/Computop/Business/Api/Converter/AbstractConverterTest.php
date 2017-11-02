<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business\Api\Converter;

use Codeception\TestCase\Test;

abstract class AbstractConverterTest extends Test
{
    /**
     * @var \SprykerEcoTest\Zed\Computop\Business\Api\Converter\ConverterTestHelper
     */
    protected $helper;

    /**
     * @param \SprykerEcoTest\Zed\Computop\Business\Api\Converter\ConverterTestHelper $helper
     *
     * @return void
     */
    protected function _inject(ConverterTestHelper $helper)
    {
        $this->helper = $helper;
    }
}
