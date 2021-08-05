<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Oms\Command\Manager;

use Generated\Shared\Transfer\OrderTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class CaptureManager extends AbstractManager
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]|\Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Oms\Persistence\SpyOmsEventTimeout[]|\Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory[]
     */
    public function getItemsBeforeCaptureState(OrderTransfer $orderTransfer): ObjectCollection
    {
        return $this
            ->queryContainer
            ->getSpySalesOrderItemsById((int)$orderTransfer->getIdSalesOrder())
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
    public function getAllItems(OrderTransfer $orderTransfer): ObjectCollection
    {
        return $this
            ->queryContainer
            ->getSpySalesOrderItemsById((int)$orderTransfer->getIdSalesOrder())
            ->useStateQuery()
            ->endUse()
            ->find();
    }
}
