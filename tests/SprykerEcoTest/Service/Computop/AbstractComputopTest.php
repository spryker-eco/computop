<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Service\Computop;

use Codeception\TestCase\Test;
use SprykerEcoTest\Service\Computop\Mapper\ComputopMapperTestHelper;

abstract class AbstractComputopTest extends Test
{
    /**
     * @var \SprykerEcoTest\Service\Computop\ComputopServiceTestHelper
     */
    protected $helper;

    /**
     * @var \SprykerEcoTest\Service\Computop\Mapper\ComputopMapperTestHelper
     */
    protected $mapperHelper;

    /**
     * @param \SprykerEcoTest\Service\Computop\ComputopServiceTestHelper $helper
     * @param \SprykerEcoTest\Service\Computop\Mapper\ComputopMapperTestHelper $mapperHelper
     *
     * @return void
     */
    protected function _inject(ComputopServiceTestHelper $helper, ComputopMapperTestHelper $mapperHelper)
    {
        $this->helper = $helper;
        $this->mapperHelper = $mapperHelper;
    }
}
