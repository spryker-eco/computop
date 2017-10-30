<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business;

use Codeception\TestCase\Test;
use SprykerEcoTest\Zed\Computop\Api\ApiPaymentTestHelper;
use SprykerEcoTest\Zed\Computop\Module\FunctionalModule;
use SprykerEcoTest\Zed\Computop\Order\OrderPaymentTestHelper;

abstract class AbstractSetUpTest extends Test
{
    /**
     * @var \SprykerEcoTest\Zed\Computop\Module\FunctionalModule
     */
    protected $helper;

    /**
     * @var \SprykerEcoTest\Zed\Computop\Order\OrderPaymentTestHelper
     */
    protected $orderHelper;

    /**
     * @var \SprykerEcoTest\Zed\Computop\Api\ApiPaymentTestHelper
     */
    protected $apiHelper;

    /**
     * @param \SprykerEcoTest\Zed\Computop\Module\FunctionalModule $helper
     * @param \SprykerEcoTest\Zed\Computop\Order\OrderPaymentTestHelper $orderHelper
     * @param \SprykerEcoTest\Zed\Computop\Api\ApiPaymentTestHelper $apiHelper
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
