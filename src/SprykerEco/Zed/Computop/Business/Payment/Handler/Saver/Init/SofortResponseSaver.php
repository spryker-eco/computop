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
    public function save(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $computopSofortInitResponseTransfer = $quoteTransfer->getPaymentOrFail()->getComputopSofortOrFail()->getSofortInitResponseOrFail();
        $this->setPaymentEntity($computopSofortInitResponseTransfer->getHeaderOrFail()->getTransId());

        if ($computopSofortInitResponseTransfer->getHeaderOrFail()->getIsSuccess()) {
            $this->getTransactionHandler()->handleTransaction(
                function () use ($computopSofortInitResponseTransfer): void {
                    $this->savePaymentComputopEntity($computopSofortInitResponseTransfer);
                    $this->savePaymentComputopDetailEntity($computopSofortInitResponseTransfer);
                    $this->savePaymentComputopOrderItemsEntities();
                },
            );
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopSofortInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopEntity(ComputopSofortInitResponseTransfer $responseTransfer): void
    {
        $this->getPaymentEntity()
            ->setPayId($responseTransfer->getHeader()->getPayId())
            ->setXId($responseTransfer->getHeader()->getXId())
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopSofortInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopDetailEntity(ComputopSofortInitResponseTransfer $responseTransfer): void
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
    protected function savePaymentComputopOrderItemsEntities(): void
    {
        foreach ($this->getPaymentEntity()->getSpyPaymentComputopOrderItems() as $item) {
            $item->setStatus($this->config->getOmsStatusCaptured());
            $item->save();
        }
    }
}
