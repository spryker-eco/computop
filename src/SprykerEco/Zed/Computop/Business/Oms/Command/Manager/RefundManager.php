<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Oms\Command\Manager;

use Generated\Shared\Transfer\OrderTransfer;

class RefundManager extends AbstractManager
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    public function getAmount(OrderTransfer $orderTransfer)
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
    protected function isShipmentRefundNeeded(OrderTransfer $orderTransfer)
    {
        $itemsBeforeRefundState = count($this->getItemsBeforeRefundState($orderTransfer));

        $itemsToRefundCount = count($orderTransfer->getItems());

        return ($itemsBeforeRefundState - $itemsToRefundCount) === 0;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrderItem[]
     */
    protected function getItemsBeforeRefundState(OrderTransfer $orderTransfer)
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
