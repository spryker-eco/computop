<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Service\Computop;

use Codeception\TestCase\Test;
use Computop\Helper\Unit\Service\ComputopServiceTestHelper;
use Computop\Helper\Unit\Service\Mapper\ComputopMapperTestHelper;

abstract class AbstractComputopTest extends Test
{

    /**
     * @var \Computop\Helper\Unit\Service\ComputopServiceTestHelper
     */
    protected $helper;

    /**
     * @var \Computop\Helper\Unit\Service\Mapper\ComputopMapperTestHelper
     */
    protected $mapperHelper;

    /**
     * @param \Computop\Helper\Unit\Service\ComputopServiceTestHelper $helper
     * @param \Computop\Helper\Unit\Service\Mapper\ComputopMapperTestHelper $mapperHelper
     *
     * @return void
     */
    protected function _inject(ComputopServiceTestHelper $helper, ComputopMapperTestHelper $mapperHelper)
    {
        $this->helper = $helper;
        $this->mapperHelper = $mapperHelper;
    }

}
