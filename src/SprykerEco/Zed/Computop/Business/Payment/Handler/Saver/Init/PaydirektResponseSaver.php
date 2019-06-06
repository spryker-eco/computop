<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init;

use Generated\Shared\Transfer\ComputopPaydirektInitResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class PaydirektResponseSaver extends AbstractResponseSaver
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function save(QuoteTransfer $quoteTransfer)
    {
        $responseTransfer = $quoteTransfer->getPayment()->getComputopPaydirekt()->getPaydirektInitResponse();
        $this->setPaymentEntity($responseTransfer->getHeader()->getTransId());
        if ($responseTransfer->getHeader()->getIsSuccess()) {
            $this->getTransactionHandler()->handleTransaction(
                function () use ($responseTransfer) {
                    $this->savePaymentComputopEntity($responseTransfer);
                    $this->savePaymentComputopDetailEntity($responseTransfer);
                    $this->savePaymentComputopOrderItemsEntities();
                    $this->triggerEvent($this->getPaymentEntity());
                }
            );
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaydirektInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopEntity(ComputopPaydirektInitResponseTransfer $responseTransfer)
    {
        $paymentEntity = $this->getPaymentEntity();
        $paymentEntity->setPayId($responseTransfer->getHeader()->getPayId());
        $paymentEntity->setXId($responseTransfer->getHeader()->getXId());
        $paymentEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaydirektInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopDetailEntity(ComputopPaydirektInitResponseTransfer $responseTransfer)
    {
        $paymentEntityDetails = $this->getPaymentEntity()->getSpyPaymentComputopDetail();
        $paymentEntityDetails->fromArray($responseTransfer->toArray());
        $paymentEntityDetails->setCustomerTransactionId($responseTransfer->getCustomerTransactionId());
        $paymentEntityDetails->save();
    }

    /**
     * @return void
     */
    protected function savePaymentComputopOrderItemsEntities()
    {
        foreach ($this->getPaymentEntity()->getSpyPaymentComputopOrderItems() as $item) {
            $item->setStatus($this->config->getOmsStatusAuthorized());
            $item->save();
        }
    }

    /**
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop $paymentEntity $paymentEntity
     *
     * @return void
     */
    protected function triggerEvent($paymentEntity)
    {
        $orderItems = $this
            ->queryContainer
            ->getSpySalesOrderItemsById($paymentEntity->getFkSalesOrder())
            ->find();

        $this->omsFacade->triggerEvent(
            $this->config->getOmsAuthorizeEventName(),
            $orderItems,
            []
        );
    }
}
