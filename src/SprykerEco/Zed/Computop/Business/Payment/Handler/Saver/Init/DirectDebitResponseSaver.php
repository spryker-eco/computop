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
     * @return void
     */
    public function handle(QuoteTransfer $quoteTransfer)
    {
        $responseTransfer = $quoteTransfer->getPayment()->getComputopDirectDebit()->getDirectDebitInitResponse();

        $this->handleDatabaseTransaction(function () use ($responseTransfer) {
            $this->saveComputopDetails($responseTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopDirectDebitInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function saveComputopDetails(ComputopDirectDebitInitResponseTransfer $responseTransfer)
    {
        if (!$responseTransfer->getHeader()->getIsSuccess()) {
            return;
        }

        /** @var \Orm\Zed\Computop\Persistence\SpyPaymentComputop $paymentEntity */
        $paymentEntity = $this->getPaymentEntity($responseTransfer->getHeader()->getTransId());

        $paymentEntity->setPayId($responseTransfer->getHeader()->getPayId());
        $paymentEntity->setXid($responseTransfer->getHeader()->getXId());
        $paymentEntity->save();

        $paymentEntityDetails = $paymentEntity->getSpyPaymentComputopDetail();
        $paymentEntityDetails->fromArray($responseTransfer->toArray());
        $paymentEntityDetails->save();
    }
}
