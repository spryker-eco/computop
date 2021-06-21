<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Persistence;

use Generated\Shared\Transfer\ComputopPaymentComputopOrderItemCollectionTransfer;
use Generated\Shared\Transfer\ComputopPaymentComputopTransfer;
use Generated\Shared\Transfer\ComputopSalesOrderItemCollectionTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopPersistenceFactory getFactory();
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

        if ($computopPaymentEntity === null) {
            return $computopPaymentTransfer;
        }

        return $this->getFactory()
            ->createComputopEntityMapper()
            ->mapComputopPaymentEntityToComputopPaymentTransfer($computopPaymentEntity, $computopPaymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopSalesOrderItemCollectionTransfer
     */
    public function getComputopSalesOrderItemsCollection(
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
    ): ComputopSalesOrderItemCollectionTransfer {
        $salesOrderItemsCollection = $this
            ->getFactory()->createSpySalesOrderItemQuery()
            ->getById($computopPaymentComputopTransfer->getFKSalesOrder())
            ->find();

        $computopSalesOrderItemCollectionTransfer = $this->getFactory()
            ->createComputopEntityMapper()
            ->mapSalesOrderItemsCollectionToComputopSalesOrderItemCollectionTransfer(
                $salesOrderItemsCollection,
                new ComputopSalesOrderItemCollectionTransfer()
            );

        return $computopSalesOrderItemCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPaymentComputopOrderItemCollectionTransfer
     */
    public function getComputopPaymentComputopOrderItemsCollection(
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
    ): ComputopPaymentComputopOrderItemCollectionTransfer {
        $computopPaymentComputopOrderItemsEntityCollection = $this->getFactory()
            ->createPaymentComputopOrderItemQuery()
            ->getByFkPaymentComputop($computopPaymentComputopTransfer->getFkPaymentComputop())
            ->find();

        $computopPaymentComputopOrderItemsCollectionTransfer = $this->getFactory()
            ->createComputopEntityMapper()
            ->mapPaymentComputopOrderItemEntityCollectionToComputopPaymentComputopOrderItemTransferCollection(
                $computopPaymentComputopOrderItemsEntityCollection,
                new ComputopPaymentComputopOrderItemCollectionTransfer()
            );

        return $computopPaymentComputopOrderItemsCollectionTransfer;
    }
}
