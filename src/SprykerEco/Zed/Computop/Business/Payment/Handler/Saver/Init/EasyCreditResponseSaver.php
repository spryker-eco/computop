<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init;

use Generated\Shared\Transfer\ComputopEasyCreditInitResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class EasyCreditResponseSaver extends AbstractResponseSaver
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function handle(QuoteTransfer $quoteTransfer)
    {
        $responseTransfer = $quoteTransfer->getPayment()->getComputopEasyCredit()->getEasyCreditInitResponse();

        $this->handleDatabaseTransaction(function () use ($responseTransfer) {
            $this->saveComputopDetails($responseTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopEasyCreditInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function saveComputopDetails(ComputopEasyCreditInitResponseTransfer $responseTransfer)
    {
        if (!$responseTransfer->getHeader()->getIsSuccess()) {
            return;
        }

        /** @var \Orm\Zed\Computop\Persistence\SpyPaymentComputop $paymentEntity */
        $paymentEntity = $this->getPaymentEntity($responseTransfer->getHeader()->getTransId());

        $paymentEntity->setPayId($responseTransfer->getHeader()->getPayId());
        $paymentEntity->setXId($responseTransfer->getHeader()->getXId());
        $paymentEntity->save();

        $paymentEntityDetails = $paymentEntity->getSpyPaymentComputopDetail();
        $paymentEntityDetails->fromArray($responseTransfer->toArray());
        $paymentEntityDetails->save();

        $orderItems = $this
            ->queryContainer
            ->getSpySalesOrderItemsById($paymentEntity->getFkSalesOrder())
            ->find();

        foreach ($orderItems as $selectedItem) {
            foreach ($paymentEntity->getSpyPaymentComputopOrderItems() as $item) {
                if ($item->getFkSalesOrderItem() !== $selectedItem->getIdSalesOrderItem()) {
                    continue;
                }
                $item->setStatus($this->config->getOmsStatusInitialized());
                $item->save();
            }
        }
    }
}