<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Computop;

use Codeception\TestCase\Test;
use Computop\Helper\Functional\Api\ApiPaymentTestHelper;
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
     * @var \Computop\Helper\Functional\Api\ApiPaymentTestHelper
     */
    protected $apiHelper;

    /**
     * @param \Computop\Module\FunctionalModule $helper
     * @param \Computop\Helper\Functional\Order\OrderPaymentTestHelper $orderHelper
     * @param \Computop\Helper\Functional\Api\ApiPaymentTestHelper $apiHelper
     *
     * @return void
     */
    protected function _inject(FunctionalModule $helper, OrderPaymentTestHelper $orderHelper, ApiPaymentTestHelper $apiHelper)
    {
        $this->helper = $helper;
        $this->orderHelper = $orderHelper;
        $this->apiHelper = $apiHelper;
    }
}
