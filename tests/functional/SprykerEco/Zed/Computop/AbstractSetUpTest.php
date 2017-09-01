<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Computop;

use Codeception\TestCase\Test;
use Computop\Helper\Functional\Order\OrderPaymentTestHelper;
use Computop\Module\FunctionalModule;

abstract class AbstractSetUpTest extends Test
{

    /**
     * @var \Computop\Module\FunctionalModule
     */
    protected $helper;

    /**
     * @var \Computop\Helper\Functional\Order\OrderPaymentTestHelper
     */
    protected $orderHelper;

    /**
     * @param \Computop\Module\FunctionalModule $helper
     * @param \Computop\Helper\Functional\Order\OrderPaymentTestHelper $orderHelper
     *
     * @return void
     */
    protected function _inject(FunctionalModule $helper, OrderPaymentTestHelper $orderHelper)
    {
        $this->helper = $helper;
        $this->orderHelper = $orderHelper;
    }

}
