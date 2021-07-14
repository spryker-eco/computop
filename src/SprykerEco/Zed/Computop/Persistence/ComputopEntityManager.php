<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Persistence;

use Generated\Shared\Transfer\ComputopNotificationTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
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
            $paymentComputopOrderItemEntity->setIsPaymentConfirmed((bool)$computopNotificationTransfer->getIsSuccess());
            $paymentComputopOrderItemEntity->save();
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    public function savePaymentResponse(TransferInterface $responseTransfer): void
    {
        $header = $responseTransfer->requireHeader()->getHeader();
        $header
            ->requireTransId()
            ->requirePayId()
            ->requireXid();

        $paymentEntity = $this->getFactory()
            ->createPaymentComputopQuery()
            ->filterByTransId($header->getTransId())
            ->findOne();

        $paymentEntity
            ->setPayId($header->getPayId())
            ->setXId($header->getXId())
            ->save();

        $this->savePaymentDetailEntity($paymentEntity->getSpyPaymentComputopDetail(), $responseTransfer);

        $this->saveAuthorizedPaymentOrderItems($paymentEntity->getSpyPaymentComputopOrderItems());
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
        $paymentEntityDetails->setCustomerTransactionId($responseTransfer->getCustomerTransactionId());
        $paymentEntityDetails->save();
    }

    /**
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem[]|\Propel\Runtime\Collection\ObjectCollection $paymentComputopOrderItems
     *
     * @return void
     */
    public function saveAuthorizedPaymentOrderItems(ObjectCollection $paymentComputopOrderItems): void
    {
        $authorizedStatus = $this->getFactory()->getConfig()->getOmsStatusAuthorized();
        foreach ($paymentComputopOrderItems as $paymentComputopOrderItem) {
            $paymentComputopOrderItem->setStatus($authorizedStatus);
            $paymentComputopOrderItem->save();
        }
    }
}
