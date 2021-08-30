<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Persistence;

use Generated\Shared\Transfer\ComputopNotificationTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail as ChildSpyPaymentComputopDetail;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

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
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $responseTransfer
     * @param string|null $paymentStatus
     *
     * @return void
     */
    public function savePaymentResponse(TransferInterface $responseTransfer, ?string $paymentStatus = null): void;

    /**
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail $paymentEntityDetails
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $responseTransfer
     *
     * @return void
     */
    public function savePaymentDetailEntity(
        ChildSpyPaymentComputopDetail $paymentEntityDetails,
        TransferInterface $responseTransfer
    ): void;

    /**
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem[]|\Propel\Runtime\Collection\ObjectCollection $paymentComputopOrderItems
     *
     * @return void
     */
    public function saveAuthorizedPaymentOrderItems(ObjectCollection $paymentComputopOrderItems): void;
}
