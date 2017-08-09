<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;
use SprykerEco\Shared\Computop\ComputopConstants;

class IsCancelledPlugin implements ConditionInterface
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        /** @var \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem $computopOrderItem */
        $computopOrderItem = $orderItem->getSpyPaymentComputopOrderItems()->getLast();

        return ($computopOrderItem->getStatus() === ComputopConstants::COMPUTOP_OMS_STATUS_CANCELLED);
    }

}
