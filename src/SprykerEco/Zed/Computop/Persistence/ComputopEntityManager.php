<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Persistence;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopNotificationTransfer;
use Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail;
use Propel\Runtime\Collection\ObjectCollection;
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

        $paymentComputopNotificationEntity
            ->fromArray($computopNotificationTransfer->modifiedToArray())
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopNotificationTransfer $computopNotificationTransfer
     * @param string|null $orderItemsStatus
     *
     * @return bool
     */
    public function updatePaymentComputopOrderItemPaymentConfirmation(
        ComputopNotificationTransfer $computopNotificationTransfer,
        ?string $orderItemsStatus = null
    ): bool {
        /** @var \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem[]|\Propel\Runtime\Collection\ObjectCollection $paymentComputopOrderItemEntities */
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
            $paymentComputopOrderItemEntity
                ->setIsPaymentConfirmed((bool)$computopNotificationTransfer->getIsSuccess())
                ->setStatus($orderItemsStatus ?? $paymentComputopOrderItemEntity->getStatus())
                ->save();
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop|null $paymentComputopEntity
     *
     * @return void
     */
    public function updatePaymentComputopEntityByComputopApiResponseHeaderTransfer(
        ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer,
        ?SpyPaymentComputop $paymentComputopEntity = null
    ): void {
        if (!$paymentComputopEntity) {
            $paymentComputopEntity = $this->getFactory()->createPaymentComputopQuery()
                ->filterByTransId($computopApiResponseHeaderTransfer->getTransId())
                ->findOne();
        }

        if (!$paymentComputopEntity) {
            return;
        }

        $paymentComputopEntity
            ->setPayId($computopApiResponseHeaderTransfer->getPayId())
            ->setXId($computopApiResponseHeaderTransfer->getXId())
            ->save();
    }

    /**
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail $paymentComputopDetailEntity
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer
     *
     * @return void
     */
    public function updatePaymentComputopDetailEntityByComputopApiResponseHeaderTransfer(
        SpyPaymentComputopDetail $paymentComputopDetailEntity,
        ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer
    ): void {
        $paymentComputopDetailEntity->fromArray($computopPayuCeeSingleInitResponseTransfer->toArray());
        $customerTransactionId = $computopPayuCeeSingleInitResponseTransfer->getCustomerTransactionId();
        if ($customerTransactionId) {
            $paymentComputopDetailEntity->setCustomerTransactionId((int)$customerTransactionId);
        }

        $paymentComputopDetailEntity->save();
    }

    /**
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem[]|\Propel\Runtime\Collection\ObjectCollection $paymentComputopOrderItemEntities
     * @param string $paymentStatus
     *
     * @return void
     */
    public function updatePaymentComputopOrderItemsStatus(ObjectCollection $paymentComputopOrderItemEntities, string $paymentStatus): void
    {
        foreach ($paymentComputopOrderItemEntities as $paymentComputopOrderItem) {
            $paymentComputopOrderItem->setStatus($paymentStatus);
            $paymentComputopOrderItem->save();
        }
    }
}
