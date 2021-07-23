<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Oms\Command\Manager;

use Generated\Shared\Transfer\OrderTransfer;

class CaptureManager extends AbstractManager
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]|\Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory[]|\Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Oms\Persistence\SpyOmsEventTimeout[]
     */
    public function getItemsBeforeCaptureState(OrderTransfer $orderTransfer)
    {
        return $this
            ->queryContainer
            ->getSpySalesOrderItemsById($orderTransfer->getIdSalesOrder())
            ->useStateQuery()
            ->filterByName_In((array)$this->config->getBeforeCaptureStatuses())
            ->endUse()
            ->find();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]|\Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Oms\Persistence\SpyOmsEventTimeout[]|\Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory[]
     */
    public function getAllItems(OrderTransfer $orderTransfer)
    {
        return $this
            ->queryContainer
            ->getSpySalesOrderItemsById($orderTransfer->getIdSalesOrder())
            ->useStateQuery()
            ->endUse()
            ->find();
    }
}
