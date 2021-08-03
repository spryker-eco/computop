<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init;

use Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;

class PayuCeeSingleResponseSaver extends AbstractResponseSaver
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function save(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (
            !$quoteTransfer->getPayment() ||
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
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer|null $responseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopOrderItemsEntities(?ComputopPayuCeeSingleInitResponseTransfer $responseTransfer): void
    {
        $status = $this->getPaymentStatus($responseTransfer);

        foreach ($this->getPaymentEntity()->getSpyPaymentComputopOrderItems() as $item) {
            $item->setStatus($status);
            $item->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer|null $responseTransfer
     *
     * @return void
     */
    protected function executeSavePaymentResponseTransaction(?ComputopPayuCeeSingleInitResponseTransfer $responseTransfer)
    {
        $this->savePaymentComputopEntity($responseTransfer);
        $this->savePaymentComputopDetailEntity($responseTransfer);
        $this->savePaymentComputopOrderItemsEntities($responseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer|null $responseTransfer
     *
     * @return string
     */
    protected function getPaymentStatus(?ComputopPayuCeeSingleInitResponseTransfer $responseTransfer): string
    {
        $responseStatus = $this->getResponseStatus($responseTransfer);
        if (!$responseTransfer) {
            return $this->config->getOmsStatusNew();
        }

        if ($responseStatus === ComputopConfig::AUTHORIZE_REQUEST_STATUS) {
            return $this->config->getOmsStatusAuthorizeRequest();
        }

        $responseDescription = $responseTransfer->getHeader()->getDescription();
        if ($responseStatus === ComputopConfig::SUCCESS_OK && $responseDescription === ComputopConfig::SUCCESS_STATUS) {
            return $this->config->getOmsStatusAuthorized();
        }

        return $this->config->getOmsStatusNew();
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer|null $responseTransfer
     *
     * @return string|null
     */
    protected function getResponseStatus(?ComputopPayuCeeSingleInitResponseTransfer $responseTransfer): ?string
    {
        if ($responseTransfer && $responseTransfer->getHeader()) {
            return $responseTransfer->getHeader()->getStatus();
        }

        return null;
    }
}
