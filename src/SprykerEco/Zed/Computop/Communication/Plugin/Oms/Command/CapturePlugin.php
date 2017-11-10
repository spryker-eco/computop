<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\Base\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \SprykerEco\Zed\Computop\Business\ComputopFacade getFacade()
 */
class CapturePlugin extends AbstractComputopPlugin implements CommandByOrderInterface
{
    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected $orderEntity;

    /**
     *
     * {@inheritdoc}
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
        $computopHeaderPayment = $this->createComputopHeaderPayment($orderEntity);
        $this->getFacade()->capturePaymentRequest($orderEntity, $computopHeaderPayment);

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getAmount(OrderTransfer $orderTransfer)
    {
        if ($this->isFirstCapture($this->getOrderEntity())) {
            return $orderTransfer->getTotals()->getGrandTotal();
        }

        return $orderTransfer->getTotals()->getSubtotal() - $orderTransfer->getTotals()->getDiscountTotal();
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return bool
     */
    protected function isFirstCapture(SpySalesOrder $orderEntity)
    {
        $itemsBeforeCaptureStateCount = count($this->getItemsBeforeCaptureState($orderEntity));
        $allItemsCount = count($this->getAllItems($orderEntity));

        return $itemsBeforeCaptureStateCount === $allItemsCount;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return array
     */
    protected function getItemsBeforeCaptureState(SpySalesOrder $orderEntity)
    {
        return SpySalesOrderItemQuery::create()
            ->filterByFkSalesOrder($orderEntity->getIdSalesOrder())
            ->useStateQuery()
            ->filterByName_In(
                (array) $this->getConfig()->getBeforeCaptureStatuses()
            )
            ->endUse()
            ->find();
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return array
     */
    protected function getAllItems(SpySalesOrder $orderEntity)
    {
        return SpySalesOrderItemQuery::create()
            ->filterByFkSalesOrder($orderEntity->getIdSalesOrder())
            ->useStateQuery()
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
