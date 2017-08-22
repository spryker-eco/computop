<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopPersistenceFactory getFactory()
 */
class ComputopQueryContainer extends AbstractQueryContainer implements ComputopQueryContainerInterface
{

    /**
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
     * @api
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery
     */
    protected function queryPayments()
    {
        return $this
            ->getFactory()
            ->createPaymentComputopQuery();
    }

    /**
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItemQuery
     */
    protected function queryPaymentOrderItems()
    {
        return $this
            ->getFactory()
            ->createPaymentComputopOrderItemQuery();
    }

}
