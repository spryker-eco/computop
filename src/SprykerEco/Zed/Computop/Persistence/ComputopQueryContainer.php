<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Persistence;

use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItemQuery;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopPersistenceFactory getFactory()
 */
class ComputopQueryContainer extends AbstractQueryContainer implements ComputopQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idPayment
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery
     */
    public function queryPaymentById($idPayment)
    {
        return $this
            ->queryPayments()
            ->filterByIdPaymentComputop($idPayment);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery
     */
    public function queryPaymentByOrderId($idOrder)
    {
        return $this
            ->queryPayments()
            ->filterByFkSalesOrder($idOrder);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $idPay
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery
     */
    public function queryPaymentByPayId($idPay)
    {
        return $this
            ->queryPayments()
            ->filterByPayId($idPay);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $idTransaction
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery
     */
    public function queryPaymentByTransactionId($idTransaction)
    {
        return $this
            ->queryPayments()
            ->filterByTransId($idTransaction);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $orderItemId
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItemQuery
     */
    public function queryPaymentItemByOrderItemId($orderItemId)
    {
        return $this
            ->queryPaymentOrderItems()
            ->filterByFkSalesOrderItem($orderItemId);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function getSpySalesOrderItemsById($idSalesOrder)
    {
        return $this
            ->getFactory()
            ->getSpySalesOrderItemQuery()
            ->filterByFkSalesOrder($idSalesOrder);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery
     */
    protected function queryPayments(): SpyPaymentComputopQuery
    {
        return $this
            ->getFactory()
            ->getPaymentComputopQuery();
    }

    /**
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItemQuery
     */
    protected function queryPaymentOrderItems(): SpyPaymentComputopOrderItemQuery
    {
        return $this
            ->getFactory()
            ->getPaymentComputopOrderItemQuery();
    }
}
