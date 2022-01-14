<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init;

use Generated\Shared\Transfer\ComputopPayNowInitResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class PayNowResponseSaver extends AbstractResponseSaver
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function save(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $responseTransfer = $quoteTransfer->getPaymentOrFail()->getComputopPayNowOrFail()->getPayNowInitResponseOrFail();
        $this->setPaymentEntity($responseTransfer->getHeaderOrFail()->getTransId());
        if ($responseTransfer->getHeaderOrFail()->getIsSuccess()) {
            $this->getTransactionHandler()->handleTransaction(
                function () use ($responseTransfer): void {
                    $this->savePaymentComputopEntity($responseTransfer);
                    $this->savePaymentComputopDetailEntity($responseTransfer);
                    $this->savePaymentComputopOrderItemsEntities();
                },
            );
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayNowInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopEntity(ComputopPayNowInitResponseTransfer $responseTransfer): void
    {
        $this->getPaymentEntity()
            ->setPayId($responseTransfer->getHeaderOrFail()->getPayId())
            ->setXId($responseTransfer->getHeaderOrFail()->getXId())
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayNowInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopDetailEntity(ComputopPayNowInitResponseTransfer $responseTransfer): void
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
