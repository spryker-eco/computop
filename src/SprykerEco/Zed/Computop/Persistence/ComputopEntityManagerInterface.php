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
     *
     * @return bool
     */
    public function updatePaymentComputopOrderItemPaymentConfirmation(
        ComputopNotificationTransfer $computopNotificationTransfer
    ): bool;

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopDetailTransfer $computopPaymentComputopDetailTransfer
     *
     * @return void
     */
    public function updateComputopPaymentDetail(
        ComputopPaymentComputopDetailTransfer $computopPaymentComputopDetailTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     *
     * @return void
     */
    public function updateComputopPayment(ComputopPaymentComputopTransfer $computopPaymentComputopTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopOrderItemTransfer $computopPaymentComputopOrderItemTransfer
     *
     * @return void
     */
    public function updateComputopPaymentComputopOrderItem(ComputopPaymentComputopOrderItemTransfer $computopPaymentComputopOrderItemTransfer): void;
}
