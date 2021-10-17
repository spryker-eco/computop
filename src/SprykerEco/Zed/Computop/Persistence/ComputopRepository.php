<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Persistence;

use Generated\Shared\Transfer\ComputopPaymentComputopDetailTransfer;
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
     * @param string $transactionId
     *
     * @return \Generated\Shared\Transfer\ComputopPaymentComputopTransfer|null
     */
    public function findComputopPaymentByComputopTransId(string $transactionId): ?ComputopPaymentComputopTransfer
    {
        $computopPaymentEntity = $this->getFactory()
            ->getPaymentComputopQuery()
            ->filterByTransId($transactionId)
            ->findOne();

        if ($computopPaymentEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createComputopMapper()
            ->mapComputopPaymentEntityToComputopPaymentTransfer($computopPaymentEntity, new ComputopPaymentComputopTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopSalesOrderItemCollectionTransfer
     */
    public function getComputopSalesOrderItemsCollection(
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
    ): ComputopSalesOrderItemCollectionTransfer {
        $salesOrderItemsCollection = $this->getFactory()
            ->getSpySalesOrderItemQuery()
            ->filterByFkSalesOrder($computopPaymentComputopTransfer->getFKSalesOrder())
            ->find();

        if ($salesOrderItemsCollection->count() === 0) {
            return new ComputopSalesOrderItemCollectionTransfer();
        }

        return $this->getFactory()
            ->createComputopMapper()
            ->mapSalesOrderItemsCollectionToComputopSalesOrderItemCollectionTransfer(
                $salesOrderItemsCollection,
                new ComputopSalesOrderItemCollectionTransfer()
            );
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
            ->getPaymentComputopOrderItemQuery()
            ->filterByFkPaymentComputop($computopPaymentComputopTransfer->getIdPaymentComputop())
            ->find();

        if ($computopPaymentComputopOrderItemsEntityCollection->count() === 0) {
            return new ComputopPaymentComputopOrderItemCollectionTransfer();
        }

        return $this->getFactory()
            ->createComputopMapper()
            ->mapPaymentComputopOrderItemEntityCollectionToComputopPaymentComputopOrderItemTransferCollection(
                $computopPaymentComputopOrderItemsEntityCollection,
                new ComputopPaymentComputopOrderItemCollectionTransfer()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPaymentComputopDetailTransfer|null
     */
    public function findComputopPaymentDetail(
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
    ): ?ComputopPaymentComputopDetailTransfer {
        $paymentComputopDetailEntity = $this->getFactory()
            ->getPaymentComputopDetailQuery()
            ->filterByIdPaymentComputop($computopPaymentComputopTransfer->getIdPaymentComputop())
            ->findOne();

        if ($paymentComputopDetailEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createComputopMapper()
            ->mapPaymentComputopDetailEntityToComputopPaymentComputopDetailTransfer(
                $paymentComputopDetailEntity,
                new ComputopPaymentComputopDetailTransfer()
            );
    }
}
