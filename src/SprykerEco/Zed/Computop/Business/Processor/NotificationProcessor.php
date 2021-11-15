<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Processor;

use Generated\Shared\Transfer\ComputopNotificationTransfer;
use Generated\Shared\Transfer\ComputopPaymentComputopTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface;
use SprykerEco\Zed\Computop\Persistence\ComputopRepositoryInterface;

class NotificationProcessor implements NotificationProcessorInterface
{
    use TransactionTrait;

    /**
     * @var \SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface
     */
    protected $computopEntityManager;

    /**
     * @var \SprykerEco\Zed\Computop\Persistence\ComputopRepositoryInterface
     */
    protected $computopRepository;

    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $computopConfig;

    /**
     * @param \SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface $computopEntityManager
     * @param \SprykerEco\Zed\Computop\Persistence\ComputopRepositoryInterface $computopRepository
     * @param \SprykerEco\Zed\Computop\ComputopConfig $computopConfig
     */
    public function __construct(
        ComputopEntityManagerInterface $computopEntityManager,
        ComputopRepositoryInterface $computopRepository,
        ComputopConfig $computopConfig
    ) {
        $this->computopEntityManager = $computopEntityManager;
        $this->computopRepository = $computopRepository;
        $this->computopConfig = $computopConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopNotificationTransfer $computopNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopNotificationTransfer
     */
    public function processNotification(
        ComputopNotificationTransfer $computopNotificationTransfer
    ): ComputopNotificationTransfer {
        return $this->getTransactionHandler()->handleTransaction(
            function () use ($computopNotificationTransfer): ComputopNotificationTransfer {
                return $this->executeSaveComputopNotificationTransaction($computopNotificationTransfer);
            },
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopNotificationTransfer $computopNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopNotificationTransfer
     */
    protected function executeSaveComputopNotificationTransaction(
        ComputopNotificationTransfer $computopNotificationTransfer
    ): ComputopNotificationTransfer {
        $this->computopEntityManager->savePaymentComputopNotification($computopNotificationTransfer);
        $paymentComputopTransfer = $this->computopRepository->findComputopPaymentByComputopTransId(
            $computopNotificationTransfer->getTransId(),
        );

        if (!$paymentComputopTransfer) {
            return $computopNotificationTransfer->setIsProcessed(false);
        }

        $this->updatePaymentComputopEntity($paymentComputopTransfer, $computopNotificationTransfer);

        $isProcessed = $this->updatePaymentComputopOrderItemsEntities($paymentComputopTransfer, $computopNotificationTransfer);

        return $computopNotificationTransfer->setIsProcessed($isProcessed);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     * @param \Generated\Shared\Transfer\ComputopNotificationTransfer $computopNotificationTransfer
     *
     * @return void
     */
    protected function updatePaymentComputopEntity(
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer,
        ComputopNotificationTransfer $computopNotificationTransfer
    ): void {
        $computopPaymentComputopTransfer
            ->setPayId($computopNotificationTransfer->getPayId())
            ->setXId($computopNotificationTransfer->getXId());

        $this->computopEntityManager->updateComputopPayment($computopPaymentComputopTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     * @param \Generated\Shared\Transfer\ComputopNotificationTransfer $computopNotificationTransfer
     *
     * @return bool
     */
    protected function updatePaymentComputopOrderItemsEntities(
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer,
        ComputopNotificationTransfer $computopNotificationTransfer
    ): bool {
        $orderItemsPaymentStatus = $this->getCurrentOrderItemEntityStatus($computopNotificationTransfer);

        $paymentComputopOrderItemsCollection = $this->computopRepository
            ->getComputopPaymentComputopOrderItemCollection($computopPaymentComputopTransfer);

        if ($paymentComputopOrderItemsCollection->getComputopPaymentComputopOrderItems()->count() === 0) {
            return false;
        }

        foreach ($paymentComputopOrderItemsCollection->getComputopPaymentComputopOrderItems() as $paymentComputopOrderItem) {
            $paymentComputopOrderItem
                ->setStatus($orderItemsPaymentStatus)
                ->setIsPaymentConfirmed((bool)$computopNotificationTransfer->getIsSuccess());

            $this->computopEntityManager->updateComputopPaymentComputopOrderItem($paymentComputopOrderItem);
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopNotificationTransfer $computopNotificationTransfer
     *
     * @return string|null
     */
    protected function getCurrentOrderItemEntityStatus(ComputopNotificationTransfer $computopNotificationTransfer): ?string
    {
        if ((int)$computopNotificationTransfer->getAmountcap() > 0) {
            return $this->computopConfig->getOmsStatusCaptured();
        }

        if ((int)$computopNotificationTransfer->getAmountauth() > 0) {
            return $this->computopConfig->getOmsStatusAuthorized();
        }

        return null;
    }
}
