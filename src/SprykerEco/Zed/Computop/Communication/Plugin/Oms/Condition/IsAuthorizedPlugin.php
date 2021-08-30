<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

/**
 * @method \SprykerEco\Zed\Computop\ComputopConfig getConfig()
 * @method \SprykerEco\Zed\Computop\Business\ComputopFacadeInterface getFacade()
 * @method \SprykerEco\Zed\Computop\Communication\ComputopCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface getQueryContainer()
 */
class IsAuthorizedPlugin extends AbstractPlugin implements ConditionInterface
{
    /**
     * {@inheritDoc}
     *  - Checks if order item has authorized status.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        /** @var \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem|null $computopOrderItem */
        $computopOrderItem = $orderItem->getSpyPaymentComputopOrderItems()->getLast();
        if ($computopOrderItem === null) {
            return false;
        }

        return in_array($computopOrderItem->getStatus(), [
            $this->getConfig()->getOmsStatusAuthorized(),
            $this->getConfig()->getOmsStatusCaptured(),
        ], true);
    }
}
