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
     * @return iterable
     */
    public function getItemsBeforeCaptureState(OrderTransfer $orderTransfer): iterable
    {
        return $this
            ->queryContainer
            ->getSpySalesOrderItemsById($orderTransfer->getIdSalesOrder())
            ->useStateQuery()
            ->filterByName_In(
                (array)$this->config->getBeforeCaptureStatuses()
            )
            ->endUse()
            ->find();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return iterable
     */
    public function getAllItems(OrderTransfer $orderTransfer): iterable
    {
        return $this
            ->queryContainer
            ->getSpySalesOrderItemsById($orderTransfer->getIdSalesOrder())
            ->useStateQuery()
            ->endUse()
            ->find();
    }
}
