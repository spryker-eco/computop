<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business;

use Codeception\Test\Unit;
use SprykerEcoTest\Zed\Computop\Business\Oms\OmsPaymentTestHelper;
use SprykerEcoTest\Zed\Computop\Module\FunctionalModule;
use SprykerEcoTest\Zed\Computop\Order\OrderPaymentTestHelper;

abstract class AbstractSetUpTest extends Unit
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
     * @var \SprykerEcoTest\Zed\Computop\Business\Oms\OmsPaymentTestHelper
     */
    protected $omsHelper;

    /**
     * @param \SprykerEcoTest\Zed\Computop\Module\FunctionalModule $helper
     * @param \SprykerEcoTest\Zed\Computop\Order\OrderPaymentTestHelper $orderHelper
     * @param \SprykerEcoTest\Zed\Computop\Business\Oms\OmsPaymentTestHelper $omsHelper
     *
     * @return void
     */
    protected function _inject(FunctionalModule $helper, OrderPaymentTestHelper $orderHelper, OmsPaymentTestHelper $omsHelper)
    {
        $this->helper = $helper;
        $this->orderHelper = $orderHelper;
        $this->omsHelper = $omsHelper;
    }
}
