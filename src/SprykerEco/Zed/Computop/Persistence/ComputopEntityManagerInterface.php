<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Persistence;

use Generated\Shared\Transfer\ComputopNotificationTransfer;
use Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer;

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
        ?string $orderItemsStatus
    ): bool;

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer
     * @param string $orderItemsStatus
     *
     * @return void
     */
    public function saveComputopPayuCeeSingleInitResponse(
        ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer,
        string $orderItemsStatus
    ): void;
}
