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
     * @return void
     */
    public function handle(QuoteTransfer $quoteTransfer)
    {
        $responseTransfer = $quoteTransfer->getPayment()->getComputopSofort()->getSofortInitResponse();

        $this->handleDatabaseTransaction(function () use ($responseTransfer) {
            $this->saveComputopDetails($responseTransfer);
            $this->triggerEvent($this->getPaymentEntity($responseTransfer->getHeader()->getTransId()));
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopSofortInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function saveComputopDetails(ComputopSofortInitResponseTransfer $responseTransfer)
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
            //Sofort sets captured status on first call
            $item->setStatus($this->config->getOmsStatusCaptured());
            $item->save();
        }

        $paymentEntityDetails = $paymentEntity->getSpyPaymentComputopDetail();
        $paymentEntityDetails->setAccountOwner($responseTransfer->getAccountOwner());
        $paymentEntityDetails->setAccountBank($responseTransfer->getAccountBank());
        $paymentEntityDetails->setBankAccountBic($responseTransfer->getBankAccountBic());
        $paymentEntityDetails->setBankAccountIban($responseTransfer->getBankAccountIban());
        $paymentEntityDetails->save();
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
