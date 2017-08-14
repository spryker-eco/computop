<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use SprykerEco\Shared\Computop\ComputopConstants;

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

        $computopCreditCardPaymentTransfer = $this->createComputopCreditCardPaymentTransfer($orderTransfer);
        $orderTransfer->setComputopCreditCard($computopCreditCardPaymentTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function createComputopCreditCardPaymentTransfer(OrderTransfer $orderTransfer)
    {
        $savedSpyPaymentComputop = $this->getFactory()->getComputopPaymentByOrderId($orderTransfer->getIdSalesOrder());

        $computopCreditCardPaymentTransfer = new ComputopCreditCardPaymentTransfer();

        $computopCreditCardPaymentTransfer->fromArray($savedSpyPaymentComputop->toArray(), true);

        $computopCreditCardPaymentTransfer->setMerchantId($this->getConfig()->getMerchantId());

        $computopCreditCardPaymentTransfer->setAmount($orderTransfer->getTotals()->getGrandTotal());

        $computopCreditCardPaymentTransfer->setCurrency(Store::getInstance()->getCurrencyIsoCode());
        $computopCreditCardPaymentTransfer->setCapture(ComputopConstants::CAPTURE_MANUAL_TYPE);
        $computopCreditCardPaymentTransfer->setResponse(ComputopConstants::RESPONSE_TYPE);
        $computopCreditCardPaymentTransfer->setOrderDesc(ComputopConstants::ORDER_DESC_SUCCESS);

        return $computopCreditCardPaymentTransfer;
    }

}
