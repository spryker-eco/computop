<?php

namespace SprykerEco\Zed\Computop\Persistence;

use Generated\Shared\Transfer\ComputopPaymentComputopDetailCollectionTransfer;
use Generated\Shared\Transfer\ComputopPaymentComputopTransfer;
use Generated\Shared\Transfer\ComputopSalesOrderItemCollectionTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method ComputopPersistenceFactory getFactory();
 */
class ComputopRepository extends AbstractRepository implements ComputopRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function getComputopPaymentByComputopTransId(string $transactionId): ComputopPaymentComputopTransfer
    {
        $paymentComputopQuery = $this->getFactory()->createPaymentComputopQuery();
        $computopPaymentEntity = $paymentComputopQuery->queryPaymentByTransactionId($transactionId)->findOne();
        $computopPaymentTransfer = new ComputopPaymentComputopTransfer();

        if (null === $computopPaymentEntity) {
            return $computopPaymentTransfer;
        }

        return $this->getFactory()
            ->createComputopEntityMapper()
            ->mapComputopPaymentEntityToComputopPaymentTransfer($computopPaymentEntity, $computopPaymentTransfer);
    }

    public function getComputopPaymentComputopOrderItems(ComputopPaymentComputopTransfer $computopPaymentTransfer)
    {
        $salesOrderItemQuery = $this->getFactory()->createPaymentComputopOrderItemQuery();
        $orderItems =

        return $this->getFactory()->createComputopEntityMapper()->mapComputopSalesOrderItems
    }

    /**
     * @param ComputopPaymentComputopTransfer $computopPaymentTransfer
     *
     * @return ComputopSalesOrderItemCollectionTransfer[]
     */
    public function getSalesOrderItems(ComputopPaymentComputopTransfer $computopPaymentTransfer): array
    {
        $salesOrderItemQuery = $this->getFactory()->createSpySalesOrderItemQuery();
        $salesOrderItemsCollection = $salesOrderItemQuery
            ->getSpySalesOrderItemsById($computopPaymentTransfer->getFKSalesOrder())->find();

        return $this->getFactory()
            ->createComputopEntityMapper()
            ->mapSalesOrderItemsCollectionToComputopSalesOrderItemCollectionTransfer(
                $salesOrderItemsCollection,
                new ComputopSalesOrderItemCollectionTransfer(),
            );
    }

    public function getComputopPaymentComputopDetailCollection(
        ComputopPaymentComputopTransfer $computopPaymentTransfer
    ): ComputopPaymentComputopDetailCollectionTransfer
    {

    }
}
