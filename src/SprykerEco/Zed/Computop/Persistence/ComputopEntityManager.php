<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Persistence;

use Generated\Shared\Transfer\ComputopNotificationTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use SprykerEco\Zed\Computop\ComputopConfig;

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
            $orderItemEntityStatus = $this->getCurrentOrderItemEntityStatus($computopNotificationTransfer, $paymentComputopOrderItemEntity);
            $paymentComputopOrderItemEntity
                ->setIsPaymentConfirmed((bool)$computopNotificationTransfer->getIsSuccess())
                ->setStatus($orderItemEntityStatus)
                ->save();
        }

        return true;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $responseTransfer
     * @param string|null $paymentStatus
     *
     * @return void
     */
    public function savePaymentResponse(TransferInterface $responseTransfer, ?string $paymentStatus = null): void
    {
        $header = $responseTransfer->requireHeader()
            ->getHeader()
                ->requireTransId()
                ->requirePayId()
                ->requireXid();

        $paymentEntity = $this->getFactory()
            ->createPaymentComputopQuery()
            ->filterByTransId($header->getTransId())
            ->findOne();

        if (!$paymentEntity) {
            return;
        }

        $paymentEntity
            ->setPayId($header->getPayId())
            ->setXId($header->getXId())
            ->save();

        $this->savePaymentDetailEntity($paymentEntity->getSpyPaymentComputopDetail(), $responseTransfer);

        $this->saveAuthorizedPaymentOrderItems($paymentEntity->getSpyPaymentComputopOrderItems(), $paymentStatus);
    }

    /**
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail $paymentEntityDetails
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    public function savePaymentDetailEntity(
        SpyPaymentComputopDetail $paymentEntityDetails,
        TransferInterface $responseTransfer
    ): void {
        $paymentEntityDetails->fromArray($responseTransfer->toArray());
        $customerTransactionId = $responseTransfer->getCustomerTransactionId();
        if ($customerTransactionId) {
            $paymentEntityDetails->setCustomerTransactionId((int)$customerTransactionId);
        }

        $paymentEntityDetails->save();
    }

    /**
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem[]|\Propel\Runtime\Collection\ObjectCollection $paymentComputopOrderItems
     * @param string|null $paymentStatus
     *
     * @return void
     */
    public function saveAuthorizedPaymentOrderItems(ObjectCollection $paymentComputopOrderItems, ?string $paymentStatus = null): void
    {
        $paymentStatus = $paymentStatus ?? $this->getFactory()->getConfig()->getOmsStatusAuthorized();
        foreach ($paymentComputopOrderItems as $paymentComputopOrderItem) {
            $paymentComputopOrderItem->setStatus($paymentStatus);
            $paymentComputopOrderItem->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopNotificationTransfer $computopNotificationTransfer
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem $paymentComputopOrderItemEntity
     *
     * @return string
     */
    protected function getCurrentOrderItemEntityStatus(
        ComputopNotificationTransfer $computopNotificationTransfer,
        SpyPaymentComputopOrderItem $paymentComputopOrderItemEntity
    ): string {
        if (
            $paymentComputopOrderItemEntity->getStatus() === ComputopConfig::OMS_STATUS_NEW &&
            (int)$computopNotificationTransfer->getAmountauth() > 0
        ) {
            return $this->getFactory()->getConfig()->getOmsStatusAuthorized();
        }

        if (
            $paymentComputopOrderItemEntity->getStatus() === ComputopConfig::OMS_STATUS_AUTHORIZED &&
            (int)$computopNotificationTransfer->getAmountcap() === (int)$computopNotificationTransfer->getAmountauth()
        ) {
            return $this->getFactory()->getConfig()->getOmsStatusCaptured();
        }

        return $paymentComputopOrderItemEntity->getStatus();
    }
}
