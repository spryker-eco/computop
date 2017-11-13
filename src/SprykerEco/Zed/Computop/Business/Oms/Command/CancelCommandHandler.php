<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Oms\Command;

use Generated\Shared\Transfer\ComputopHeaderPaymentTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Computop\Business\Oms\Command\Manager\CancelManagerInterface;
use SprykerEco\Zed\Computop\Business\Payment\Handler\HandlerInterface;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMessengerFacadeInterface;

class CancelCommandHandler extends AbstractCommandHandler
{
    /**
     * @var \SprykerEco\Zed\Computop\Business\Payment\Handler\HandlerInterface
     */
    protected $inquirePaymentHandler;

    /**
     * @var \SprykerEco\Zed\Computop\Business\Payment\Handler\HandlerInterface
     */
    protected $reversePaymentHandler;

    /**
     * @var \SprykerEco\Zed\Computop\Business\Oms\Command\Manager\CancelManagerInterface
     */
    protected $manager;

    /**
     * @var \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \SprykerEco\Zed\Computop\Business\Payment\Handler\HandlerInterface $inquirePaymentHandler
     * @param \SprykerEco\Zed\Computop\Business\Payment\Handler\HandlerInterface $reversePaymentHandler
     * @param \SprykerEco\Zed\Computop\Business\Oms\Command\Manager\CancelManagerInterface $cancelItemManager
     * @param \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        HandlerInterface $inquirePaymentHandler,
        HandlerInterface $reversePaymentHandler,
        CancelManagerInterface $cancelItemManager,
        ComputopToMessengerFacadeInterface $messengerFacade
    ) {
        $this->inquirePaymentHandler = $inquirePaymentHandler;
        $this->reversePaymentHandler = $reversePaymentHandler;
        $this->manager = $cancelItemManager;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function handle(array $orderItems, OrderTransfer $orderTransfer)
    {
        if ($this->isAllOrderCancellation($orderItems, $orderTransfer)) {
            $computopHeaderPayment = $this->createComputopHeaderPayment($orderTransfer);

            return $this->cancelOrderAuthorization($orderItems, $orderTransfer, $computopHeaderPayment);
        }

        return $this->cancelOrderItems($orderItems);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItemsToCancel
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function isAllOrderCancellation(array $orderItemsToCancel, OrderTransfer $orderTransfer)
    {
        $allOrderItemsCount = count($orderTransfer->getItems());
        $cancelledOrderItemsCount = count($this->manager->getCanceledItems($orderTransfer));
        $orderItemsToCancelCount = count($orderItemsToCancel);

        return ($orderItemsToCancelCount + $cancelledOrderItemsCount) === $allOrderItemsCount;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return array|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function cancelOrderAuthorization(array $orderItems, OrderTransfer $orderTransfer, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        $responseTransfer = $this->inquirePaymentHandler->handle($orderTransfer, $computopHeaderPayment);

        if ($responseTransfer->getIsAuthLast()) {
            return $this->reverseOrderAuthorizationRequest($orderTransfer, $computopHeaderPayment);
        }

        return $this->cancelOrderItems($orderItems);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function reverseOrderAuthorizationRequest(OrderTransfer $orderTransfer, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        $computopResponseTransfer = $this->reversePaymentHandler->handle($orderTransfer, $computopHeaderPayment);
        if ($computopResponseTransfer->getHeader()->getIsSuccess()) {
            $this->setInfoMessage('Authorization was reverted');
            return $computopResponseTransfer;
        }

        $this->setErrorMessage('Authorization was not reverted. Please check logs');
        return $computopResponseTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     *
     * @return array
     */
    protected function cancelOrderItems(array $orderItems)
    {
        $this->manager->changeComputopItemsStatus($orderItems);

        $this->setInfoMessage('Item(s) was(were) changed');

        return $orderItems;
    }

    /**
     * @param string $messageValue
     *
     * @return void
     */
    protected function setInfoMessage($messageValue)
    {
        $message = $this->getMessageTransfer($messageValue);

        $this
            ->messengerFacade
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

        $this
            ->messengerFacade
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
}
