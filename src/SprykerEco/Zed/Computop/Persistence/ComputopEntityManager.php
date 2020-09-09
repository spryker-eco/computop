<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Persistence;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopPersistenceFactory getFactory()
 */
class ComputopEntityManager extends AbstractEntityManager implements ComputopEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer
     *
     * @return void
     */
    public function savePaymentComputopNotification(
        ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer
    ): void {
        $paymentComputopNotificationEntity = $this->getFactory()
            ->createPaymentComputopNotificationQuery()
            ->filterByPayId($computopApiResponseHeaderTransfer->getPayId())
            ->filterByTransId($computopApiResponseHeaderTransfer->getTransId())
            ->filterByXId($computopApiResponseHeaderTransfer->getXId())
            ->findOneOrCreate();

        $paymentComputopNotificationEntity->fromArray(
            $computopApiResponseHeaderTransfer->modifiedToArray()
        );
        $paymentComputopNotificationEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer
     *
     * @return void
     */
    public function updatePaymentComputopOrderItemNotificationStatus(
        ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer
    ): void {
        $paymentComputopOrderItemEntities = $this->getFactory()
            ->createPaymentComputopOrderItemQuery()
            ->useSpyPaymentComputopQuery()
                ->filterByTransId($computopApiResponseHeaderTransfer->getTransId())
                ->filterByPayId($computopApiResponseHeaderTransfer->getPayId())
            ->endUse()
            ->find();

        foreach ($paymentComputopOrderItemEntities as $paymentComputopOrderItemEntity) {
            $paymentComputopOrderItemEntity->setNotificationStatus($computopApiResponseHeaderTransfer->getOrderItemStatus());
            $paymentComputopOrderItemEntity->save();
        }
    }
}
