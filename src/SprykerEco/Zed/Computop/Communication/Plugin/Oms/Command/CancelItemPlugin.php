<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Communication\Plugin\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByItemInterface;

/**
 * @method \SprykerEco\Zed\Computop\Business\ComputopFacade getFacade()
 */
class CancelItemPlugin extends AbstractComputopPlugin implements CommandByItemInterface
{

    /**
     * @inheritdoc
     */
    public function run(SpySalesOrderItem $orderItem, ReadOnlyArrayObject $data)
    {
        $this->getFacade()->cancelPaymentItem($orderItem);

        return [];
    }

}
