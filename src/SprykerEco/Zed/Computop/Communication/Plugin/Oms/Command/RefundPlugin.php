<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Communication\Plugin\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \SprykerEco\Zed\Computop\Business\ComputopFacadeInterface getFacade()
 * @method \SprykerEco\Zed\Computop\ComputopConfig getConfig()
 * @method \SprykerEco\Zed\Computop\Communication\ComputopCommunicationFactory getFactory()
 */
class RefundPlugin extends AbstractComputopPlugin implements CommandByOrderInterface
{
    /**
     * {@inheritDoc}
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Requires `OrderTransfer.totals` to be set.
     * - Handle Refund OMS command, make request, save response.
     *
     * @api
     *
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $orderEntity->getItems()->setData($orderItems);
        $orderTransfer = $this->getOrderTransfer($orderEntity);

        $responseTransfer = $this->getFacade()->refundCommandHandle($orderItems, $orderTransfer);

        if ($responseTransfer->getHeaderOrFail()->getIsSuccess()) {
            $refundTransfer = $this->getFactory()->getRefundFacade()->calculateRefund($orderItems, $orderEntity);
            $this->getFactory()->getRefundFacade()->saveRefund($refundTransfer);
        }

        return [];
    }
}
