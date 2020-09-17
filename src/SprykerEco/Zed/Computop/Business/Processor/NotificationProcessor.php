<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Processor;

use Generated\Shared\Transfer\ComputopNotificationTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface;

class NotificationProcessor implements NotificationProcessorInterface
{
    use TransactionTrait;

    /**
     * @var \SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface
     */
    protected $computopEntityManager;

    /**
     * @param \SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface $computopEntityManager
     */
    public function __construct(ComputopEntityManagerInterface $computopEntityManager)
    {
        $this->computopEntityManager = $computopEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopNotificationTransfer $computopNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopNotificationTransfer
     */
    public function processNotification(
        ComputopNotificationTransfer $computopNotificationTransfer
    ): ComputopNotificationTransfer {
        $this->getTransactionHandler()->handleTransaction(
            function () use ($computopNotificationTransfer): void {
                $this->executeSaveComputopNotificationTransaction($computopNotificationTransfer);
            }
        );

        return $computopNotificationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopNotificationTransfer $computopNotificationTransfer
     *
     * @return void
     */
    protected function executeSaveComputopNotificationTransaction(
        ComputopNotificationTransfer $computopNotificationTransfer
    ): void {
        $this->computopEntityManager->savePaymentComputopNotification($computopNotificationTransfer);
        $this->computopEntityManager->updatePaymentComputopOrderItemPaymentConfirmation($computopNotificationTransfer);
    }
}
