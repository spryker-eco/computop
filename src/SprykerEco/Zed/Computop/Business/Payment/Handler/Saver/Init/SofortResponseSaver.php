<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init;

use Generated\Shared\Transfer\ComputopSofortInitResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class SofortResponseSaver extends AbstractResponseSaver
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function save(QuoteTransfer $quoteTransfer)
    {
        $responseTransfer = $quoteTransfer->getPayment()->getComputopSofort()->getSofortInitResponse();
        $this->setPaymentEntity($responseTransfer->getHeader()->getTransId());
        if ($responseTransfer->getHeader()->getIsSuccess()) {
            $this->handleDatabaseTransaction(function () use ($responseTransfer) {
                $this->savePaymentComputopEntity($responseTransfer);
                $this->savePaymentComputopDetailEntity($responseTransfer);
                $this->savePaymentComputopOrderItemsEntities();
                $this->triggerEvent($this->getPaymentEntity());
            });
        }

        return $quoteTransfer;
    }

    /**
     * @param ComputopSofortInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopEntity(ComputopSofortInitResponseTransfer $responseTransfer)
    {
        $paymentEntity = $this->getPaymentEntity();
        $paymentEntity->setPayId($responseTransfer->getHeader()->getPayId());
        $paymentEntity->setXId($responseTransfer->getHeader()->getXId());
        $paymentEntity->save();
    }

    /**
     * @param ComputopSofortInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopDetailEntity(ComputopSofortInitResponseTransfer $responseTransfer)
    {
        $paymentEntityDetails = $this->getPaymentEntity()->getSpyPaymentComputopDetail();
        $paymentEntityDetails->setAccountOwner($responseTransfer->getAccountOwner());
        $paymentEntityDetails->setAccountBank($responseTransfer->getAccountBank());
        $paymentEntityDetails->setBankAccountBic($responseTransfer->getBankAccountBic());
        $paymentEntityDetails->setBankAccountIban($responseTransfer->getBankAccountIban());
        $paymentEntityDetails->save();
    }

    /**
     * @return void
     */
    protected function savePaymentComputopOrderItemsEntities()
    {
        foreach ($this->getPaymentEntity()->getSpyPaymentComputopOrderItems() as $item) {
            //Sofort sets captured status on first call
            $item->setStatus($this->config->getOmsStatusCaptured());
            $item->save();
        }
    }

    /**
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop $paymentEntity
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
            $this->config->getOmsCaptureEventName(),
            $orderItems,
            []
        );
    }
}
