<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Persistence;

use Generated\Shared\Transfer\ComputopNotificationTransfer;
use Generated\Shared\Transfer\ComputopPaymentComputopDetailTransfer;
use Generated\Shared\Transfer\ComputopPaymentComputopOrderItemTransfer;
use Generated\Shared\Transfer\ComputopPaymentComputopTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopPersistenceFactory getFactory()
 */
class ComputopEntityManager extends AbstractEntityManager implements ComputopEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ComputopNotificationTransfer $computopNotificationTransfer
     *
     * @return void
     */
    public function savePaymentComputopNotification(ComputopNotificationTransfer $computopNotificationTransfer): void
    {
        $paymentComputopNotificationEntity = $this->getFactory()
            ->getPaymentComputopNotificationQuery()
            ->filterByPayId($computopNotificationTransfer->getPayId())
            ->filterByTransId($computopNotificationTransfer->getTransId())
            ->filterByXId($computopNotificationTransfer->getXId())
            ->findOneOrCreate();

        $paymentComputopNotificationEntity->fromArray(
            $computopNotificationTransfer->modifiedToArray(),
        );
        $paymentComputopNotificationEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopDetailTransfer $computopPaymentComputopDetailTransfer
     *
     * @return void
     */
    public function updateComputopPaymentDetail(
        ComputopPaymentComputopDetailTransfer $computopPaymentComputopDetailTransfer
    ): void {
        $computopPaymentComputopDetailEntity = $this->getFactory()
            ->getPaymentComputopDetailQuery()
            ->filterByIdPaymentComputop($computopPaymentComputopDetailTransfer->getIdPaymentComputop())
            ->findOne();

        if ($computopPaymentComputopDetailEntity === null) {
            return;
        }

        $computopPaymentComputopDetailEntity = $this->getFactory()
            ->createComputopMapper()
            ->mapComputopPaymentComputopDetailTransferToPaymentComputopDetailEntity(
                $computopPaymentComputopDetailTransfer,
                $computopPaymentComputopDetailEntity,
            );

        $computopPaymentComputopDetailEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     *
     * @return void
     */
    public function updateComputopPayment(ComputopPaymentComputopTransfer $computopPaymentComputopTransfer): void
    {
        $computopPaymentComputopEntity = $this->getFactory()
            ->getPaymentComputopQuery()
            ->filterByTransId($computopPaymentComputopTransfer->getTransId())
            ->findOne();

        if ($computopPaymentComputopEntity === null) {
            return;
        }

        $computopPaymentComputopEntity = $this->getFactory()
            ->createComputopMapper()
            ->mapComputopPaymentTransferToComputopPaymentEntity(
                $computopPaymentComputopTransfer,
                $computopPaymentComputopEntity,
            );

        $computopPaymentComputopEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopOrderItemTransfer $computopPaymentComputopOrderItemTransfer
     *
     * @return void
     */
    public function updateComputopPaymentComputopOrderItem(
        ComputopPaymentComputopOrderItemTransfer $computopPaymentComputopOrderItemTransfer
    ): void {
        $paymentComputopOrderItemEntity = $this->getFactory()
            ->createComputopMapper()
            ->mapComputopPaymentComputopOrderItemTransferToPaymentComputopOrderItemEntity(
                $computopPaymentComputopOrderItemTransfer,
                new SpyPaymentComputopOrderItem(),
            );

        $paymentComputopOrderItemEntity->save();
    }
}
