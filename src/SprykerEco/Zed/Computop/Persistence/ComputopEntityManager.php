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
            ->createPaymentComputopNotificationQuery()
            ->filterByPayId($computopNotificationTransfer->getPayId())
            ->filterByTransId($computopNotificationTransfer->getTransId())
            ->filterByXId($computopNotificationTransfer->getXId())
            ->findOneOrCreate();

        $paymentComputopNotificationEntity->fromArray(
            $computopNotificationTransfer->modifiedToArray()
        );
        $paymentComputopNotificationEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopNotificationTransfer $computopNotificationTransfer
     *
     * @return bool
     */
    public function updatePaymentComputopOrderItemPaymentConfirmation(
        ComputopNotificationTransfer $computopNotificationTransfer
    ): bool {
        $paymentComputopOrderItemEntities = $this->getFactory()
            ->createPaymentComputopOrderItemQuery()
            ->useSpyPaymentComputopQuery()
            ->filterByTransId($computopNotificationTransfer->getTransId())
            ->filterByPayId($computopNotificationTransfer->getPayId())
            ->endUse()
            ->find();

        if (!$paymentComputopOrderItemEntities->count()) {
            return false;
        }

        foreach ($paymentComputopOrderItemEntities as $paymentComputopOrderItemEntity) {
            /** @var \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem $paymentComputopOrderItemEntity */
            $paymentComputopOrderItemEntity->setIsPaymentConfirmed($computopNotificationTransfer->getIsSuccess());
            $paymentComputopOrderItemEntity->save();
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopDetailTransfer $computopPaymentComputopDetailTransfer
     *
     * @return void
     */
    public function saveComputopPaymentDetail(
        ComputopPaymentComputopDetailTransfer $computopPaymentComputopDetailTransfer
    ): void {
        $computopPaymentComputopDetailEntity = $this->getFactory()
            ->createPaymentComputopDetailQuery()
            ->filterByIdPaymentComputop($computopPaymentComputopDetailTransfer->getIdPaymentComputop())
            ->findOne();

        $computopPaymentComputopDetailEntity = $this->getFactory()
            ->createComputopEntityMapper()
            ->mapComputopPaymentComputopDetailTransferToPaymentComputopDetailEntity(
                $computopPaymentComputopDetailTransfer,
                $computopPaymentComputopDetailEntity
            );

        $computopPaymentComputopDetailEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     *
     * @return void
     */
    public function saveComputopPayment(ComputopPaymentComputopTransfer $computopPaymentComputopTransfer): void
    {
        $computopPaymentComputopEntity = $this->getFactory()
            ->createPaymentComputopQuery()
            ->filterByTransId($computopPaymentComputopTransfer->getTransId())->findOne();

        $computopPaymentComputopEntity = $this->getFactory()
            ->createComputopEntityMapper()
            ->mapComputopPaymentTransferToComputopPaymentEntity(
                $computopPaymentComputopTransfer,
                $computopPaymentComputopEntity
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
            ->createComputopEntityMapper()
            ->mapComputopPaymentComputopOrderItemTransferToPaymentComputopOrderItemEntity(
                $computopPaymentComputopOrderItemTransfer,
                new SpyPaymentComputopOrderItem(),
            );

        $paymentComputopOrderItemEntity->save();
    }
}
