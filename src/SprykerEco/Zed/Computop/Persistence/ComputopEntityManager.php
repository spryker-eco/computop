<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Persistence;

use Generated\Shared\Transfer\ComputopNotificationTransfer;
use Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use SprykerEco\Shared\Computop\ComputopConfig as SharedComputopConfig;

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
        /** @var \Orm\Zed\Computop\Persistence\SpyPaymentComputop|null $paymentComputopEntity */
        $paymentComputopEntity = $this->getFactory()
            ->createPaymentComputopQuery()
            ->filterByTransId($computopNotificationTransfer->getTransId())
            ->findOne();

        if ($paymentComputopEntity === null) {
            return false;
        }

        $paymentComputopEntity
            ->setPayId($computopNotificationTransfer->getPayId())
            ->setXId($computopNotificationTransfer->getXId());

        if ($paymentComputopEntity->isModified()) {
            $paymentComputopEntity->save();
        }

        /** @var \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem[]|\Propel\Runtime\Collection\ObjectCollection $paymentComputopOrderItemEntities */
        $paymentComputopOrderItemEntities = $this->getFactory()
            ->createPaymentComputopOrderItemQuery()
            ->filterByFkPaymentComputop($paymentComputopEntity->getIdPaymentComputop())
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
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer
     *
     * @return void
     */
    public function saveComputopPayuCeeSingleInitResponse(ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer): void
    {
        $computopApiResponseHeaderTransfer = $computopPayuCeeSingleInitResponseTransfer->requireHeader()
            ->getHeader()
                ->requireTransId()
                ->requirePayId()
                ->requireXid();

        $paymentComputopEntity = $this->getFactory()
            ->createPaymentComputopQuery()
            ->filterByTransId($computopApiResponseHeaderTransfer->getTransId())
            ->findOne();

        if (!$paymentComputopEntity) {
            return;
        }

        $paymentComputopEntity
            ->setPayId($computopApiResponseHeaderTransfer->getPayId())
            ->setXId($computopApiResponseHeaderTransfer->getXId())
            ->save();

        $this->savePaymentComputopDetailEntity(
            $paymentComputopEntity->getSpyPaymentComputopDetail(),
            $computopPayuCeeSingleInitResponseTransfer
        );

        $this->savePaymentComputopOrderItems(
            $paymentComputopEntity->getSpyPaymentComputopOrderItems(),
            $this->getPaymentStatus($computopPayuCeeSingleInitResponseTransfer)
        );
    }

    /**
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail $paymentComputopDetailEntity
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopDetailEntity(
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
    protected function savePaymentComputopOrderItems(ObjectCollection $paymentComputopOrderItemEntities, string $paymentStatus): void
    {
        foreach ($paymentComputopOrderItemEntities as $paymentComputopOrderItem) {
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
        $computopConfig = $this->getFactory()->getConfig();

        if (
            (int)$computopNotificationTransfer->getAmountauth() > 0 &&
            in_array($paymentComputopOrderItemEntity->getStatus(), $computopConfig->getBeforeAuthorizeStatuses())
        ) {
            return $computopConfig->getOmsStatusAuthorized();
        }

        if (
            (int)$computopNotificationTransfer->getAmountcap() === (int)$computopNotificationTransfer->getAmountauth() &&
            in_array($paymentComputopOrderItemEntity->getStatus(), $computopConfig->getBeforeCaptureStatuses())
        ) {
            return $computopConfig->getOmsStatusCaptured();
        }

        return $paymentComputopOrderItemEntity->getStatus();
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer
     *
     * @return string
     */
    protected function getPaymentStatus(ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer): string
    {
        $computopConfig = $this->getFactory()->getConfig();

        $computopApiResponseHeaderTransfer = $computopPayuCeeSingleInitResponseTransfer->getHeader();
        if ($computopApiResponseHeaderTransfer === null) {
            return $computopConfig->getOmsStatusNew();
        }

        $responseStatus = $computopApiResponseHeaderTransfer->getStatus();
        if ($responseStatus === null) {
            return $computopConfig->getOmsStatusNew();
        }

        if ($responseStatus === SharedComputopConfig::AUTHORIZE_REQUEST_STATUS) {
            return $computopConfig->getAuthorizeRequestOmsStatus();
        }

        if (
            $responseStatus === SharedComputopConfig::SUCCESS_OK &&
            $computopApiResponseHeaderTransfer->getDescription() === SharedComputopConfig::SUCCESS_STATUS
        ) {
            return $computopConfig->getOmsStatusAuthorized();
        }

        return $computopConfig->getOmsStatusNew();
    }
}
