<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business\Api;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use SprykerEco\Shared\Computop\ComputopConfig as SharedComputopConfig;
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
     * @return array
     */
    public function createOrderItems()
    {
        $item = SpyPaymentComputopOrderItemQuery::create()->findOne();
        $idSalesOrderItem = $item->getFkSalesOrderItem();

        $orderItem = new SpySalesOrderItem();
        $orderItem->setIdSalesOrderItem($idSalesOrderItem);

        return [$orderItem];
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    public function createPaymentTransfer()
    {
        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentProvider(SharedComputopConfig::PROVIDER_NAME)
            ->setPaymentMethod(SharedComputopConfig::PAYMENT_METHOD_CREDIT_CARD)
            ->setPaymentSelection(SharedComputopConfig::PAYMENT_METHOD_CREDIT_CARD);

        return $paymentTransfer;
    }
}
