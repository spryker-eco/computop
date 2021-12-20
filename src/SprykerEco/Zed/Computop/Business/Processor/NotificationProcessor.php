<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Processor;

use Generated\Shared\Transfer\ComputopNotificationTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface;

class NotificationProcessor implements NotificationProcessorInterface
{
    use TransactionTrait;

    /**
     * @var \SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface
     */
    protected $computopEntityManager;

    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $computopConfig;

    /**
     * @param \SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface $computopEntityManager
     * @param \SprykerEco\Zed\Computop\ComputopConfig $computopConfig
     */
    public function __construct(ComputopEntityManagerInterface $computopEntityManager, ComputopConfig $computopConfig)
    {
        $this->computopEntityManager = $computopEntityManager;
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
        $isProcessed = $this->computopEntityManager->updatePaymentComputopOrderItemPaymentConfirmation(
            $computopNotificationTransfer,
            $this->getCurrentOrderItemEntityStatus($computopNotificationTransfer),
        );

        $computopNotificationTransfer->setIsProcessed($isProcessed);

        return $computopNotificationTransfer;
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
