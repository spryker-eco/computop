<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Processor;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
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
    public function __construct(
        ComputopEntityManagerInterface $computopEntityManager,
        ComputopConfig $computopConfig
    ) {
        $this->computopEntityManager = $computopEntityManager;
        $this->computopConfig = $computopConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer
     *
     * @return void
     */
    public function processNotification(ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer): void
    {
        $computopApiResponseHeaderTransfer->setOrderItemStatus(
            $this->computopConfig->getOmsStatusInitialized()
        );

        $this->getTransactionHandler()->handleTransaction(
            function () use ($computopApiResponseHeaderTransfer): void {
                $this->executeSaveComputopNotificationTransaction($computopApiResponseHeaderTransfer);
            }
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer
     *
     * @return void
     */
    protected function executeSaveComputopNotificationTransaction(
        ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer
    ): void {
        $this->computopEntityManager->savePaymentComputopNotification($computopApiResponseHeaderTransfer);

        if (!$computopApiResponseHeaderTransfer->getIsSuccess()) {
            return;
        }

        $this->computopEntityManager->updatePaymentComputopOrderItemNotificationStatus($computopApiResponseHeaderTransfer);
    }
}
