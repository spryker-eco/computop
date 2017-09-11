<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Computop\Helper\Functional\Api;

use Codeception\TestCase\Test;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\ComputopConfig;

class ApiPaymentTestHelper extends Test
{

    /**
     * @return \SprykerEco\Zed\Computop\ComputopConfig
     */
    public function createConfig()
    {
        return new ComputopConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setTotals(new TotalsTransfer());
        $orderTransfer->setCustomer(new CustomerTransfer());
        return $orderTransfer;
    }

    /**
     * @param string $payId
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputop
     */
    public function createComputopPayment($payId)
    {
        $computopPayment = new SpyPaymentComputop();
        $computopPayment->setPaymentMethod(ComputopConstants::PAYMENT_METHOD_CREDIT_CARD);
        $computopPayment->setPayId($payId);

        return $computopPayment;
    }

}
