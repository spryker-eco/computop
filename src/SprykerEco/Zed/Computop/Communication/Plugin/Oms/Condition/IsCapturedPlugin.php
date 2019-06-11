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
class IsCapturedPlugin extends AbstractPlugin implements ConditionInterface
{
    /**
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        /** @var \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem $computopOrderItem */
        $computopOrderItem = $orderItem->getSpyPaymentComputopOrderItems()->getLast();

        return $computopOrderItem->getStatus() === $this->getConfig()->getOmsStatusCaptured();
    }
}
