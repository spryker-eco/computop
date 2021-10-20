<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ComputopPaymentComputopDetailTransfer;
use Generated\Shared\Transfer\ComputopPaymentComputopOrderItemCollectionTransfer;
use Generated\Shared\Transfer\ComputopPaymentComputopOrderItemTransfer;
use Generated\Shared\Transfer\ComputopPaymentComputopTransfer;
use Generated\Shared\Transfer\ComputopSalesOrderItemCollectionTransfer;
use Generated\Shared\Transfer\ComputopSalesOrderItemTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Collection\ObjectCollection;

class ComputopMapper
{
    /**
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop $computopPaymentEntity
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPaymentComputopTransfer
     */
    public function mapComputopPaymentEntityToComputopPaymentTransfer(
        SpyPaymentComputop $computopPaymentEntity,
        ComputopPaymentComputopTransfer $computopPaymentTransfer
    ): ComputopPaymentComputopTransfer {
        $computopPaymentTransfer->fromArray($computopPaymentEntity->toArray(), true);
        $computopPaymentTransfer->setFKSalesOrder($computopPaymentEntity->getFkSalesOrder());

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentTransfer
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop $computopPaymentEntity
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputop
     */
    public function mapComputopPaymentTransferToComputopPaymentEntity(
        ComputopPaymentComputopTransfer $computopPaymentTransfer,
        SpyPaymentComputop $computopPaymentEntity
    ): SpyPaymentComputop {
        $computopPaymentEntity->fromArray($computopPaymentTransfer->modifiedToArray());

        return $computopPaymentEntity;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $salesOrderItemEntityCollection
     * @param \Generated\Shared\Transfer\ComputopSalesOrderItemCollectionTransfer $computopSalesOrderItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopSalesOrderItemCollectionTransfer
     */
    public function mapSalesOrderItemEntityCollectionToComputopSalesOrderItemCollectionTransfer(
        ObjectCollection $salesOrderItemEntityCollection,
        ComputopSalesOrderItemCollectionTransfer $computopSalesOrderItemCollectionTransfer
    ): ComputopSalesOrderItemCollectionTransfer {
        foreach ($salesOrderItemEntityCollection as $salesOrderItemEntity) {
            $computopSalesOrderItemTransfer = $this
                ->mapSalesOrderItemToComputopSalesOrderItemTransfer($salesOrderItemEntity, new ComputopSalesOrderItemTransfer());
            $computopSalesOrderItemCollectionTransfer->addComputopSalesOrderItem($computopSalesOrderItemTransfer);
        }

        return $computopSalesOrderItemCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItem
     * @param \Generated\Shared\Transfer\ComputopSalesOrderItemTransfer $computopSalesOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopSalesOrderItemTransfer
     */
    public function mapSalesOrderItemToComputopSalesOrderItemTransfer(
        SpySalesOrderItem $salesOrderItem,
        ComputopSalesOrderItemTransfer $computopSalesOrderItemTransfer
    ): ComputopSalesOrderItemTransfer {
        $computopSalesOrderItemTransfer->fromArray($salesOrderItem->toArray(), true);

        return $computopSalesOrderItemTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $paymentComputopOrderItemEntityCollection
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopOrderItemCollectionTransfer $computopPaymentComputopOrderItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPaymentComputopOrderItemCollectionTransfer
     */
    public function mapPaymentComputopOrderItemEntityCollectionToComputopPaymentComputopOrderItemTransferCollection(
        ObjectCollection $paymentComputopOrderItemEntityCollection,
        ComputopPaymentComputopOrderItemCollectionTransfer $computopPaymentComputopOrderItemCollectionTransfer
    ): ComputopPaymentComputopOrderItemCollectionTransfer {
        foreach ($paymentComputopOrderItemEntityCollection as $paymentComputopOrderItemEntity) {
            $computopPaymentOrderItemTransfer = $this
                ->mapPaymentComputopOrderItemEntityToComputopPaymentComputopOrderItemTransfer(
                    $paymentComputopOrderItemEntity,
                    new ComputopPaymentComputopOrderItemTransfer()
                );
            $computopPaymentComputopOrderItemCollectionTransfer->addComputopPaymentComputopOrderItem($computopPaymentOrderItemTransfer);
        }

        return $computopPaymentComputopOrderItemCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem $spyPaymentComputopOrderItem
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopOrderItemTransfer $computopPaymentComputopOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPaymentComputopOrderItemTransfer
     */
    public function mapPaymentComputopOrderItemEntityToComputopPaymentComputopOrderItemTransfer(
        SpyPaymentComputopOrderItem $spyPaymentComputopOrderItem,
        ComputopPaymentComputopOrderItemTransfer $computopPaymentComputopOrderItemTransfer
    ): ComputopPaymentComputopOrderItemTransfer {
        $computopPaymentComputopOrderItemTransfer->fromArray($spyPaymentComputopOrderItem->toArray(), true);
        $computopPaymentComputopOrderItemTransfer->setIsNew($spyPaymentComputopOrderItem->isNew());

        return $computopPaymentComputopOrderItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopOrderItemTransfer $computopPaymentComputopOrderItemTransfer
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem $spyPaymentComputopOrderItem
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem
     */
    public function mapComputopPaymentComputopOrderItemTransferToPaymentComputopOrderItemEntity(
        ComputopPaymentComputopOrderItemTransfer $computopPaymentComputopOrderItemTransfer,
        SpyPaymentComputopOrderItem $spyPaymentComputopOrderItem
    ): SpyPaymentComputopOrderItem {
        $spyPaymentComputopOrderItem->fromArray($computopPaymentComputopOrderItemTransfer->toArray());
        $spyPaymentComputopOrderItem->setNew($computopPaymentComputopOrderItemTransfer->getIsNew());

        return $spyPaymentComputopOrderItem;
    }

    /**
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail $paymentComputopDetailEntity
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopDetailTransfer $computopPaymentComputopDetailTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPaymentComputopDetailTransfer
     */
    public function mapPaymentComputopDetailEntityToComputopPaymentComputopDetailTransfer(
        SpyPaymentComputopDetail $paymentComputopDetailEntity,
        ComputopPaymentComputopDetailTransfer $computopPaymentComputopDetailTransfer
    ): ComputopPaymentComputopDetailTransfer {
        $computopPaymentComputopDetailTransfer->fromArray($paymentComputopDetailEntity->toArray(), true);

        return $computopPaymentComputopDetailTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopDetailTransfer $computopPaymentComputopTransfer
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail $paymentComputopDetailEntity
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail
     */
    public function mapComputopPaymentComputopDetailTransferToPaymentComputopDetailEntity(
        ComputopPaymentComputopDetailTransfer $computopPaymentComputopTransfer,
        SpyPaymentComputopDetail $paymentComputopDetailEntity
    ): SpyPaymentComputopDetail {
        $paymentComputopDetailEntity->fromArray($computopPaymentComputopTransfer->toArray());

        return $paymentComputopDetailEntity;
    }
}
