<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Oms\Command\Manager;

use Generated\Shared\Transfer\OrderTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class RefundManager extends AbstractManager
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    public function getAmount(OrderTransfer $orderTransfer): int
    {
        if ($this->config->isRefundShipmentPriceEnabled() && $this->isShipmentRefundNeeded($orderTransfer)) {
            return $orderTransfer->getTotals()->getRefundTotal();
        }

        return $orderTransfer->getTotals()->getSubtotal() - $orderTransfer->getTotals()->getDiscountTotal();
    }

    /**
     * Check is last refund
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function isShipmentRefundNeeded(OrderTransfer $orderTransfer): bool
    {
        $itemsBeforeRefundState = count($this->getItemsBeforeRefundState($orderTransfer));

        $itemsToRefundCount = count($orderTransfer->getItems());

        return ($itemsBeforeRefundState - $itemsToRefundCount) === 0;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]|\Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory[]|\Orm\Zed\Oms\Persistence\SpyOmsEventTimeout[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getItemsBeforeRefundState(OrderTransfer $orderTransfer): ObjectCollection
    {
        return $this
            ->queryContainer
            ->getSpySalesOrderItemsById($orderTransfer->getIdSalesOrder())
            ->useStateQuery()
            ->filterByName_In(
                (array)$this->config->getBeforeRefundStatuses()
            )
            ->endUse()
            ->find();
    }
}
