<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init;

use Generated\Shared\Transfer\ComputopDirectDebitInitResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class DirectDebitResponseSaver extends AbstractResponseSaver
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function save(QuoteTransfer $quoteTransfer)
    {
        $responseTransfer = $quoteTransfer->getPayment()->getComputopDirectDebit()->getDirectDebitInitResponse();
        $this->setPaymentEntity($responseTransfer->getHeader()->getTransId());
        if ($responseTransfer->getHeader()->getIsSuccess()) {
            $this->handleDatabaseTransaction(function () use ($responseTransfer) {
                $this->savePaymentComputopEntity($responseTransfer);
                $this->savePaymentComputopDetailEntity($responseTransfer);
            });
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopDirectDebitInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopEntity(ComputopDirectDebitInitResponseTransfer $responseTransfer)
    {
        $paymentEntity = $this->getPaymentEntity();
        $paymentEntity->setPayId($responseTransfer->getHeader()->getPayId());
        $paymentEntity->setXId($responseTransfer->getHeader()->getXId());
        $paymentEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopDirectDebitInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopDetailEntity(ComputopDirectDebitInitResponseTransfer $responseTransfer)
    {
        $paymentEntityDetails = $this->getPaymentEntity()->getSpyPaymentComputopDetail();
        $paymentEntityDetails->fromArray($responseTransfer->toArray());
        $paymentEntityDetails->save();
    }
}
