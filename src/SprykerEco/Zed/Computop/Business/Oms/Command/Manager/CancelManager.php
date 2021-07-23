<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Oms\Command\Manager;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\ActiveQuery\Criteria;

class CancelManager extends AbstractManager implements CancelManagerInterface
{
    /**
     * @param array $orderItems
     *
     * @return array
     */
    public function changeComputopItemsStatus(array $orderItems)
    {
        $this->getTransactionHandler()->handleTransaction(
            function () use ($orderItems) {
                foreach ($orderItems as $orderItem) {
                    $this->changeStatus($orderItem);
                }
            }
        );

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]|\Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory[]|\Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Oms\Persistence\SpyOmsEventTimeout[]
     */
    public function getCanceledItems(OrderTransfer $orderTransfer)
    {
        return $this
            ->queryContainer
            ->getSpySalesOrderItemsById($orderTransfer->getIdSalesOrder())
            ->useStateQuery()
            ->filterByName(
                $this->config->getOmsStatusCancelled(),
                Criteria::EQUAL
            )
            ->endUse()
            ->find();
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return void
     */
    protected function changeStatus(SpySalesOrderItem $orderItem)
    {
        $computopOrderItem = $this
            ->queryContainer
            ->queryPaymentItemByOrderItemId($orderItem->getIdSalesOrderItem())
            ->findOne();

        $computopOrderItem->setStatus($this->config->getOmsStatusCancelled());
        $computopOrderItem->save();
    }
}
