<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
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
        $orderTransfer = $this->getOrderTransfer($orderEntity, $orderItems);
        $computopHeaderPayment = $this->createComputopHeaderPayment($orderEntity);

        if ($this->isAllOrderCancellation($orderItems, $orderEntity)) {
            return $this->cancelOrderAuthorization($orderItems, $orderTransfer, $computopHeaderPayment);
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
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop $savedComputopEntity
     *
     * @return array
     */
    protected function cancelOrderAuthorization(array $orderItems, OrderTransfer $orderTransfer, SpyPaymentComputop $savedComputopEntity)
    {
        $responseTransfer = $this->getFacade()->inquirePaymentRequest($orderTransfer, $savedComputopEntity);

        if ($responseTransfer->getIsAuthLast()) {
            return $this->reverseOrderAuthorizationRequest($orderTransfer, $savedComputopEntity);
        }

        return $this->cancelOrderItems($orderItems);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop $savedComputopEntity
     *
     * @return array
     */
    protected function reverseOrderAuthorizationRequest(OrderTransfer $orderTransfer, SpyPaymentComputop $savedComputopEntity)
    {
        $computopResponseTransfer = $this->getFacade()->reversePaymentRequest($orderTransfer, $savedComputopEntity);
        if ($computopResponseTransfer->getHeader()->getIsSuccess()) {
            $this->setInfoMessage('Authorization was reverted');
            return [];
        }

        $this->setErrorMessage('Authorization was not reverted. Please check logs');
        return [];
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
        $message = $this->getMessageTransfer($messageValue);

        $this->getFactory()
            ->getFlashMessengerFacade()
            ->addInfoMessage($message);
    }

    /**
     * @param string $messageValue
     *
     * @return void
     */
    protected function setErrorMessage($messageValue)
    {
        $messageTransfer = $this->getMessageTransfer($messageValue);

        $this->getFactory()
            ->getFlashMessengerFacade()
            ->addErrorMessage($messageTransfer);
    }

    /**
     * @param string $messageValue
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function getMessageTransfer($messageValue)
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($messageValue);

        return $messageTransfer;
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
