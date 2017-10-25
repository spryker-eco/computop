<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Order;

use Generated\Shared\Transfer\ComputopPaydirektOrderResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;

class PaydirektResponseHandler extends AbstractResponseHandler
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function handle(QuoteTransfer $quoteTransfer)
    {
        $responseTransfer = $quoteTransfer->getPayment()->getComputopPaydirekt()->getPaydirektOrderResponse();

        $this->handleDatabaseTransaction(function () use ($responseTransfer) {
            $this->saveComputopOrderDetails($responseTransfer);
            $this->triggerEvent($this->getPaymentEntity($responseTransfer->getHeader()->getTransId()));
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaydirektOrderResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function saveComputopOrderDetails(ComputopPaydirektOrderResponseTransfer $responseTransfer)
    {
        if (!$responseTransfer->getHeader()->getIsSuccess()) {
            return;
        }

        /** @var \Orm\Zed\Computop\Persistence\SpyPaymentComputop $paymentEntity */
        $paymentEntity = $this->getPaymentEntity($responseTransfer->getHeader()->getTransId());

        $paymentEntity->setPayId($responseTransfer->getHeader()->getPayId());
        $paymentEntity->setXid($responseTransfer->getHeader()->getXId());
        $paymentEntity->save();

        foreach ($paymentEntity->getSpyPaymentComputopOrderItems() as $item) {
            //Paydirekt sets authorize status on first call
            $item->setStatus($this->config->getOmsStatusAuthorized());
            $item->save();
        }

        $paymentEntityDetails = $paymentEntity->getSpyPaymentComputopDetail();

        //TODO: update data
        $paymentEntityDetails->save();
    }

    /**
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop $paymentEntity $paymentEntity
     *
     * @return void
     */
    protected function triggerEvent($paymentEntity)
    {
        $orderItems = SpySalesOrderItemQuery::create()
            ->filterByFkSalesOrder($paymentEntity->getFkSalesOrder())
            ->find();

        $this->omsFacade->triggerEvent(
            $this->config->getOmsAuthorizeEventName(),
            $orderItems,
            []
        );
    }
}
