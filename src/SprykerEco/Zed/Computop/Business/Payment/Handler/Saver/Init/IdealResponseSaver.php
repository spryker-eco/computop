<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init;

use Generated\Shared\Transfer\ComputopIdealInitResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class IdealResponseSaver extends AbstractResponseSaver
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function handle(QuoteTransfer $quoteTransfer)
    {
        $responseTransfer = $quoteTransfer->getPayment()->getComputopIdeal()->getIdealInitResponse();

        $this->handleDatabaseTransaction(function () use ($responseTransfer) {
            $this->saveComputopDetails($responseTransfer);
            $this->triggerEvent($this->getPaymentEntity($responseTransfer->getHeader()->getTransId()));
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopIdealInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function saveComputopDetails(ComputopIdealInitResponseTransfer $responseTransfer)
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
            //Ideal sets captured status on first call
            $item->setStatus($this->config->getOmsStatusCaptured());
            $item->save();
        }

        $paymentEntityDetails = $paymentEntity->getSpyPaymentComputopDetail();
        $paymentEntityDetails->setAccountOwner($responseTransfer->getAccountOwner());
        $paymentEntityDetails->setAccountBank($responseTransfer->getAccountBank());
        $paymentEntityDetails->setBankAccountBic($responseTransfer->getBankAccountBic());
        $paymentEntityDetails->setBankAccountIban($responseTransfer->getBankAccountIban());
        $paymentEntityDetails->setRefNr($responseTransfer->getRefNr());
        $paymentEntityDetails->setPaymentPurpose($responseTransfer->getPaymentPurpose());
        $paymentEntityDetails->setPaymentGuarantee($responseTransfer->getPaymentGuarantee());
        $paymentEntityDetails->setErrorText($responseTransfer->getErrorText());
        $paymentEntityDetails->setTransactionID($responseTransfer->getTransactionID());
        $paymentEntityDetails->setPlain($responseTransfer->getPlain());
        $paymentEntityDetails->setCustom($responseTransfer->getCustom());

        $paymentEntityDetails->save();
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
            $this->config->getOmsCaptureEventName(),
            $orderItems,
            []
        );
    }
}