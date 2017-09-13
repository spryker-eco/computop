<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\ComputopHeaderPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \SprykerEco\Zed\Computop\Business\ComputopFacade getFacade()
 * @method \SprykerEco\Zed\Computop\ComputopConfig getConfig()
 * @method \SprykerEco\Zed\Computop\Communication\ComputopCommunicationFactory getFactory()
 */
abstract class AbstractComputopPlugin extends AbstractPlugin
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param array $salesOrderItems
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer(SpySalesOrder $orderEntity, array $salesOrderItems = [])
    {
        $orderTransfer = $this
            ->getFactory()
            ->getSalesFacade()
            ->getOrderByIdSalesOrder(
                $orderEntity->getIdSalesOrder()
            );

        $orderTransfer = $this
            ->getFactory()
            ->getCalculationFacade()
            ->recalculateOrder($orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer
     */
    protected function createComputopHeaderPayment(OrderTransfer $orderTransfer)
    {
        $headerPayment = new ComputopHeaderPaymentTransfer();
        $savedComputopEntity = $this->getSavedComputopEntity($orderTransfer->getIdSalesOrder());
        $headerPayment->fromArray($savedComputopEntity->toArray(), true);
        $headerPayment->setAmount($this->getAmount($orderTransfer));

        return $headerPayment;
    }

    /**
     * @param integer $idSalesOrder
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputop
     */
    protected function getSavedComputopEntity($idSalesOrder)
    {
        return $this
            ->getFactory()
            ->getSavedComputopEntity($idSalesOrder);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getAmount(OrderTransfer $orderTransfer)
    {
        return $orderTransfer->getTotals()->getGrandTotal();
    }

}
