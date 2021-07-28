<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init;

use Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class PayuCeeSingleResponseSaver extends AbstractResponseSaver
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function save(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$quoteTransfer->getPayment() ||
            !$quoteTransfer->getPayment()->getComputopPayuCeeSingle() ||
            !$quoteTransfer->getPayment()->getComputopPayuCeeSingle()->getPayuCeeSingleInitResponse()
        ) {
            return $quoteTransfer;
        }

        $responseTransfer = $quoteTransfer->getPayment()->getComputopPayuCeeSingle()->getPayuCeeSingleInitResponse();
        if ($responseTransfer->getHeader()->getIsSuccess()) {
            $this->setPaymentEntity($responseTransfer->getHeader()->getTransId());

            $this->getTransactionHandler()->handleTransaction(function () use ($responseTransfer) {
                $this->executeSavePaymentResponseTransaction($responseTransfer);
            });
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopEntity(ComputopPayuCeeSingleInitResponseTransfer $responseTransfer)
    {
        $paymentEntity = $this->getPaymentEntity();
        $paymentEntity->setPayId($responseTransfer->getHeader()->getPayId());
        $paymentEntity->setXId($responseTransfer->getHeader()->getXId());
        $paymentEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopDetailEntity(ComputopPayuCeeSingleInitResponseTransfer $responseTransfer)
    {
        $paymentEntityDetails = $this->getPaymentEntity()->getSpyPaymentComputopDetail();
        $paymentEntityDetails->fromArray($responseTransfer->toArray());

        if ($responseTransfer->getCustomerTransactionId()) {
            $paymentEntityDetails->setCustomerTransactionId((int)$responseTransfer->getCustomerTransactionId());
        }

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
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer|null $responseTransfer
     */
    protected function executeSavePaymentResponseTransaction(?ComputopPayuCeeSingleInitResponseTransfer $responseTransfer)
    {
        $this->savePaymentComputopEntity($responseTransfer);
        $this->savePaymentComputopDetailEntity($responseTransfer);
        $this->savePaymentComputopOrderItemsEntities();
    }
}
