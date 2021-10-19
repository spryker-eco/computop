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

interface ComputopRepositoryInterface
{
    /**
     * @param string $transactionId
     *
     * @return \Generated\Shared\Transfer\ComputopPaymentComputopTransfer|null
     */
    public function findComputopPaymentByComputopTransId(string $transactionId): ?ComputopPaymentComputopTransfer;

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopSalesOrderItemCollectionTransfer
     */
    public function getComputopSalesOrderItemCollection(
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
    ): ComputopSalesOrderItemCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPaymentComputopOrderItemCollectionTransfer
     */
    public function getComputopPaymentComputopOrderItemCollection(
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
    ): ComputopPaymentComputopOrderItemCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPaymentComputopDetailTransfer|null
     */
    public function findComputopPaymentDetail(
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
    ): ?ComputopPaymentComputopDetailTransfer;
}
