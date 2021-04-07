<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Persistence;

interface ComputopQueryContainerInterface
{
    /**
     * Specification:
     * - Returns `SpyPaymentComputopQuery` filtered by ID.
     *
     * @api
     *
     * @param int $idPayment
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery
     */
    public function queryPaymentById($idPayment);

    /**
     * Specification:
     * - Returns `SpyPaymentComputopQuery` filtered by order ID.
     *
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery
     */
    public function queryPaymentByOrderId($idOrder);

    /**
     * Specification:
     * - Returns `SpyPaymentComputopQuery` filtered by pay ID.
     *
     * @api
     *
     * @param string $idPay
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery
     */
    public function queryPaymentByPayId($idPay);

    /**
     *  Specification:
     * - Returns `SpyPaymentComputopQuery` filtered by transaction ID.
     *
     * @api
     *
     * @param string $idTransaction
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery
     */
    public function queryPaymentByTransactionId($idTransaction);

    /**
     *  Specification:
     * - Returns `SpyPaymentComputopQuery` filtered by order item ID.
     *
     * @api
     *
     * @param int $orderItemId
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItemQuery
     */
    public function queryPaymentItemByOrderItemId($orderItemId);

    /**
     *  Specification:
     * - Returns `SpySalesOrderItemQuery` filtered by order ID.
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function getSpySalesOrderItemsById($idSalesOrder);
}
