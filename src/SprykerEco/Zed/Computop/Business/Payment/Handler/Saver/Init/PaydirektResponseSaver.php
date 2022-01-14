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
    public function save(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $computopPaydirektInitResponseTransfer = $quoteTransfer->getPaymentOrFail()->getComputopPaydirektOrFail()->getPaydirektInitResponseOrFail();
        $this->setPaymentEntity($computopPaydirektInitResponseTransfer->getHeaderOrFail()->getTransIdOrFail());
        if ($computopPaydirektInitResponseTransfer->getHeaderOrFail()->getIsSuccess()) {
            $this->getTransactionHandler()->handleTransaction(
                function () use ($computopPaydirektInitResponseTransfer): void {
                    $this->savePaymentComputopEntity($computopPaydirektInitResponseTransfer);
                    $this->savePaymentComputopDetailEntity($computopPaydirektInitResponseTransfer);
                    $this->savePaymentComputopOrderItemsEntities();
                },
            );
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaydirektInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopEntity(ComputopPaydirektInitResponseTransfer $responseTransfer): void
    {
        $this->getPaymentEntity()
            ->setPayId($responseTransfer->getHeaderOrFail()->getPayId())
            ->setXId($responseTransfer->getHeaderOrFail()->getXId())
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaydirektInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopDetailEntity(ComputopPaydirektInitResponseTransfer $responseTransfer): void
    {
        $paymentEntityDetails = $this->getPaymentEntity()->getSpyPaymentComputopDetail();
        $paymentEntityDetails->fromArray($responseTransfer->toArray());
        $paymentEntityDetails->setCustomerTransactionId($responseTransfer->getCustomerTransactionId());
        $paymentEntityDetails->save();
    }

    /**
     * @return void
     */
    protected function savePaymentComputopOrderItemsEntities(): void
    {
        foreach ($this->getPaymentEntity()->getSpyPaymentComputopOrderItems() as $item) {
            $item->setStatus($this->config->getOmsStatusAuthorized());
            $item->save();
        }
    }
}
