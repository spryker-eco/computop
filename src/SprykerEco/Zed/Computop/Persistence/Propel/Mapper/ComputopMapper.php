<?php

namespace SprykerEco\Zed\Computop\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ComputopPaymentComputopTransfer;
use Generated\Shared\Transfer\ComputopSalesOrderItemCollectionTransfer;
use Generated\Shared\Transfer\ComputopSalesOrderItemTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Collection\Collection;

class ComputopMapper
{
    /**
     * @param SpyPaymentComputop $computopPaymentEntity
     * @param ComputopPaymentComputopTransfer $computopPaymentTransfer
     * @return ComputopPaymentComputopTransfer
     */
    public function mapComputopPaymentEntityToComputopPaymentTransfer(
        SpyPaymentComputop $computopPaymentEntity,
        ComputopPaymentComputopTransfer $computopPaymentTransfer
    ): ComputopPaymentComputopTransfer
    {
        $computopPaymentTransfer->fromArray($computopPaymentEntity->toArray(), true);

        return $computopPaymentTransfer;
    }

    /**
     * @param Collection $salesOrderItemsCollection
     * @param ComputopSalesOrderItemCollectionTransfer $computopSalesOrderItemCollectionTransfer
     *
     * @return ComputopSalesOrderItemCollectionTransfer[]
     */
    public function mapSalesOrderItemsCollectionToComputopSalesOrderItemCollectionTransfer(
        Collection $salesOrderItemsCollection,
        ComputopSalesOrderItemCollectionTransfer $computopSalesOrderItemCollectionTransfer
    ): array
    {
        $computopSalesOrderItemCollectionTransfers = [];
        foreach ($salesOrderItemsCollection as $salesOrderItem) {
            $computopSalesOrderItemTransfer = $this->
            mapSalesOrderItemToComputopSalesOrderItemTransfer($salesOrderItem, new ComputopSalesOrderItemTransfer());
            $computopSalesOrderItemCollectionTransfers[] = $computopSalesOrderItemTransfer;
        }

        return $computopSalesOrderItemCollectionTransfers;
    }

    /**
     * @param SpySalesOrderItem $salesOrderItem
     * @param ComputopSalesOrderItemTransfer $computopSalesOrderItemTransfer
     *
     * @return ComputopSalesOrderItemTransfer
     */
    public function mapSalesOrderItemToComputopSalesOrderItemTransfer(
        SpySalesOrderItem $salesOrderItem,
        ComputopSalesOrderItemTransfer $computopSalesOrderItemTransfer
    ): ComputopSalesOrderItemTransfer
    {
        $computopSalesOrderItemTransfer->fromArray($salesOrderItem->toArray(), true);

        return $computopSalesOrderItemTransfer;
    }

    public function map()
    {

    }
}
