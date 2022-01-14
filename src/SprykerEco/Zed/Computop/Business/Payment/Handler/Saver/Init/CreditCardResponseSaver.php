<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init;

use Generated\Shared\Transfer\ComputopCreditCardInitResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class CreditCardResponseSaver extends AbstractResponseSaver
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function save(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $computopCreditCardInitResponseTransfer = $quoteTransfer->getPaymentOrFail()->getComputopCreditCardOrFail()->getCreditCardInitResponseOrFail();
        $this->setPaymentEntity($computopCreditCardInitResponseTransfer->getHeaderOrFail()->getTransId());
        if ($computopCreditCardInitResponseTransfer->getHeaderOrFail()->getIsSuccess()) {
            $this->getTransactionHandler()->handleTransaction(
                function () use ($computopCreditCardInitResponseTransfer): void {
                    $this->savePaymentComputopEntity($computopCreditCardInitResponseTransfer);
                    $this->savePaymentComputopDetailEntity($computopCreditCardInitResponseTransfer);
                    $this->savePaymentComputopOrderItemsEntities();
                },
            );
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopEntity(ComputopCreditCardInitResponseTransfer $responseTransfer): void
    {
        $this->getPaymentEntity()
            ->setPayId($responseTransfer->getHeaderOrFail()->getPayId())
            ->setXId($responseTransfer->getHeaderOrFail()->getXId())
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopDetailEntity(ComputopCreditCardInitResponseTransfer $responseTransfer): void
    {
        $paymentEntityDetails = $this->getPaymentEntity()->getSpyPaymentComputopDetail();
        $paymentEntityDetails->fromArray($responseTransfer->toArray());
        $paymentEntityDetails->save();
    }

    /**
     * @return void
     */
    protected function savePaymentComputopOrderItemsEntities(): void
    {
        $orderItems = $this
            ->queryContainer
            ->getSpySalesOrderItemsById($this->getPaymentEntity()->getFkSalesOrder())
            ->find();

        foreach ($orderItems as $selectedItem) {
            foreach ($this->getPaymentEntity()->getSpyPaymentComputopOrderItems() as $item) {
                if ($item->getFkSalesOrderItem() !== $selectedItem->getIdSalesOrderItem()) {
                    continue;
                }
                $item->setStatus($this->config->getOmsStatusInitialized());
                $item->save();
            }
        }
    }
}
