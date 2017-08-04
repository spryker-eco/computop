<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

interface CancelItemManagerInterface
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return void
     */
    public function changeComputopItemStatus(SpySalesOrderItem $orderItem);

}
