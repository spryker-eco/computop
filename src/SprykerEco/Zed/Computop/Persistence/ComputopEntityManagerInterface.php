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

interface ComputopEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ComputopNotificationTransfer $computopNotificationTransfer
     *
     * @return void
     */
    public function savePaymentComputopNotification(ComputopNotificationTransfer $computopNotificationTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\ComputopNotificationTransfer $computopNotificationTransfer
     * @param string|null $orderItemsStatus
     *
     * @return bool
     */
    public function updatePaymentComputopOrderItemPaymentConfirmation(
        ComputopNotificationTransfer $computopNotificationTransfer,
        ?string $orderItemsStatus = null
    ): bool;

    /**
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop|null $paymentComputopEntity
     *
     * @return void
     */
    public function updatePaymentComputopEntityByComputopApiResponseHeaderTransfer(
        ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer,
        ?SpyPaymentComputop $paymentComputopEntity = null
    ): void;

    /**
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail $paymentComputopDetailEntity
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer
     *
     * @return void
     */
    public function updatePaymentComputopDetailEntityByComputopApiResponseHeaderTransfer(
        SpyPaymentComputopDetail $paymentComputopDetailEntity,
        ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer
    ): void;

    /**
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem[]|\Propel\Runtime\Collection\ObjectCollection $paymentComputopOrderItemEntities
     * @param string $paymentStatus
     *
     * @return void
     */
    public function updatePaymentComputopOrderItemsStatus(ObjectCollection $paymentComputopOrderItemEntities, string $paymentStatus): void;
}
