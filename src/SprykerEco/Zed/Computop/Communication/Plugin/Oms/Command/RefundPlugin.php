<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\Base\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;
use SprykerEco\Shared\Computop\ComputopConstants;

/**
 * @method \SprykerEco\Zed\Computop\Business\ComputopFacade getFacade()
 */
class RefundPlugin extends AbstractComputopPlugin implements CommandByOrderInterface
{

    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected $orderEntity;

    /**
     *
     * Inherit
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $this->orderEntity = $orderEntity;

        $orderEntity->getItems()->setData($orderItems);
        $orderEntity = $this->getOrderTransfer($orderEntity, $orderItems);
        $this->getFacade()->refundPaymentRequest($orderEntity);

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return integer
     */
    protected function getAmount(OrderTransfer $orderTransfer)
    {
        if ($this->getConfig()->isRefundShipmentPriceEnabled() && $this->isFirstRefund($this->getOrderEntity())) {
            return $orderTransfer->getTotals()->getRefundTotal();
        }

        return $orderTransfer->getTotals()->getSubtotal();
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return bool
     */
    protected function isFirstRefund(SpySalesOrder $orderEntity)
    {
        return count($this->getRefundedItems($orderEntity)) === 0;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return array
     */
    protected function getRefundedItems(SpySalesOrder $orderEntity)
    {
        return SpySalesOrderItemQuery::create()
            ->filterByFkSalesOrder($orderEntity->getIdSalesOrder())
            ->useStateQuery()
            ->filterByName(
                ComputopConstants::COMPUTOP_OMS_STATUS_REFUNDED,
                Criteria::EQUAL
            )
            ->endUse()
            ->find();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function getOrderEntity()
    {
        return $this->orderEntity;
    }

}
