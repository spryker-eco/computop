<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Zed\Computop\Api\Converter;

use Codeception\TestCase\Test;
use Computop\Helper\Unit\Zed\Api\Converter\ConverterTestHelper;

abstract class AbstractConverterTest extends Test
{

    /**
     * @var \Computop\Helper\Unit\Zed\Api\Converter\ConverterTestHelper
     */
    protected $helper;

    /**
     * @param \Computop\Helper\Unit\Zed\Api\Converter\ConverterTestHelper $helper
     *
     * @return void
     */
    protected function _inject(ConverterTestHelper $helper)
    {
        $this->helper = $helper;
    }

}
