<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Oms\Command;

use Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Zed\Computop\Business\Oms\Command\Manager\ManagerInterface;
use SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\HandlerInterface;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMessengerFacadeInterface;

class CancelCommandHandler extends AbstractCommandHandler
{
    /**
     * @var \SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\HandlerInterface
     */
    protected $inquirePaymentHandler;

    /**
     * @var \SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\HandlerInterface
     */
    protected $reversePaymentHandler;

    /**
     * @var \SprykerEco\Zed\Computop\Business\Oms\Command\Manager\ManagerInterface
     */
    protected $manager;

    /**
     * @var \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\HandlerInterface $inquirePaymentHandler
     * @param \SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\HandlerInterface $reversePaymentHandler
     * @param \SprykerEco\Zed\Computop\Business\Oms\Command\Manager\ManagerInterface $cancelItemManager
     * @param \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        HandlerInterface $inquirePaymentHandler,
        HandlerInterface $reversePaymentHandler,
        ManagerInterface $cancelItemManager,
        ComputopToMessengerFacadeInterface $messengerFacade
    ) {
        $this->inquirePaymentHandler = $inquirePaymentHandler;
        $this->reversePaymentHandler = $reversePaymentHandler;
        $this->manager = $cancelItemManager;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|array
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
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItemsToCancel
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function isAllOrderCancellation(array $orderItemsToCancel, OrderTransfer $orderTransfer): bool
    {
        $allOrderItemsCount = count($orderTransfer->getItems());
        $cancelledOrderItemsCount = count($this->manager->getCanceledItems($orderTransfer));
        $orderItemsToCancelCount = count($orderItemsToCancel);

        return ($orderItemsToCancelCount + $cancelledOrderItemsCount) === $allOrderItemsCount;
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|array
     */
    protected function cancelOrderAuthorization(array $orderItems, OrderTransfer $orderTransfer, ComputopApiHeaderPaymentTransfer $computopHeaderPayment)
    {
        $responseTransfer = $this->inquirePaymentHandler->handle($orderTransfer, $computopHeaderPayment);
        if ($responseTransfer->getIsAuthLast()) {
            return $this->reverseOrderAuthorizationRequest($orderTransfer, $computopHeaderPayment);
        }

        return $this->cancelOrderItems($orderItems);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function reverseOrderAuthorizationRequest(
        OrderTransfer $orderTransfer,
        ComputopApiHeaderPaymentTransfer $computopHeaderPayment
    ): TransferInterface {
        /** @var \Generated\Shared\Transfer\ComputopDirectDebitInitResponseTransfer $computopResponseTransfer */
        $computopResponseTransfer = $this->reversePaymentHandler->handle($orderTransfer, $computopHeaderPayment);
        if ($computopResponseTransfer->getHeaderOrFail()->getIsSuccess()) {
            $this->setInfoMessage('Authorization was reverted');

            return $computopResponseTransfer;
        }

        $this->setErrorMessage('Authorization was not reverted. Please check logs');

        return $computopResponseTransfer;
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     *
     * @return array
     */
    protected function cancelOrderItems(array $orderItems): array
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
    protected function setInfoMessage(string $messageValue): void
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
    protected function setErrorMessage(string $messageValue): void
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
    protected function getMessageTransfer(string $messageValue): MessageTransfer
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($messageValue);

        return $messageTransfer;
    }
}
