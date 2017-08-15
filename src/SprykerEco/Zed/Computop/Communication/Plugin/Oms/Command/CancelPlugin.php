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
class CancelPlugin extends AbstractComputopPlugin implements CommandByOrderInterface
{

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
        $orderTransfer = $this->getOrderTransfer($orderEntity, $orderItems);

        if ($this->isAllOrderCancellation($orderItems, $orderEntity)) {
            return $this->cancelOrderAuthorization($orderItems, $orderTransfer);
        }

        return $this->cancelOrderItems($orderItems);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItemsToCancel
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return bool
     */
    protected function isAllOrderCancellation(array $orderItemsToCancel, SpySalesOrder $orderEntity)
    {
        $allOrderItemsCount = count($orderEntity->getItems());
        $cancelledOrderItemsCount = count($this->getCanceledItems($orderEntity));
        $orderItemsToCancelCount = count($orderItemsToCancel);

        return ($orderItemsToCancelCount + $cancelledOrderItemsCount) === $allOrderItemsCount;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function cancelOrderAuthorization(array $orderItems, OrderTransfer $orderTransfer)
    {
        $responseTransfer = $this->getFacade()->inquirePaymentRequest($orderTransfer);

        if ($responseTransfer->getIsAuthLast()) {
            $this->reverseOrderAuthorizationRequest($orderTransfer);
            return [];
        }

        return $this->cancelOrderItems($orderItems);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function reverseOrderAuthorizationRequest(OrderTransfer $orderTransfer)
    {
        $computopResponseTransfer = $this->getFacade()->reversePaymentRequest($orderTransfer);

        $messageValue = $computopResponseTransfer->getHeader()->getIsSuccess() ?
            'Authorization was reverted' :
            'Authorization was not reverted. Please check logs';
        $this->setInfoMessage($messageValue);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     *
     * @return array
     */
    protected function cancelOrderItems(array $orderItems)
    {
        foreach ($orderItems as $orderItem) {
            $this->getFacade()->cancelPaymentItem($orderItem);
        }

        $this->setInfoMessage('Item(s) was(were) changed');

        return [];
    }

    /**
     * @param string $messageValue
     *
     * @return void
     */
    protected function setInfoMessage($messageValue)
    {
        $message = $this
            ->getFactory()
            ->createMessage()
            ->setValue($messageValue);

        $this->getFactory()
            ->getFlashMessengerFacade()
            ->addInfoMessage($message);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return array
     */
    protected function getCanceledItems(SpySalesOrder $orderEntity)
    {
        return SpySalesOrderItemQuery::create()
            ->filterByFkSalesOrder($orderEntity->getIdSalesOrder())
            ->useStateQuery()
            ->filterByName(
                ComputopConstants::COMPUTOP_OMS_STATUS_CANCELLED,
                Criteria::EQUAL
            )
            ->endUse()
            ->find();
    }

}
