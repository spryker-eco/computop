<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler;

use Generated\Shared\Transfer\ComputopHeaderPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class RefundHandler extends AbstractHandler
{
    use DatabaseTransactionHandlerTrait;

    const METHOD = 'REFUND';

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \Generated\Shared\Transfer\ComputopRefundResponseTransfer
     */
    public function handle(
        OrderTransfer $orderTransfer,
        ComputopHeaderPaymentTransfer $computopHeaderPayment
    ) {
        /** @var \Generated\Shared\Transfer\ComputopRefundResponseTransfer $responseTransfer */
        $responseTransfer = $this->request->request($orderTransfer, $computopHeaderPayment);

        $this->handleDatabaseTransaction(function () use ($responseTransfer, $orderTransfer) {
            $this->saveComputopOrderDetails($responseTransfer, $orderTransfer);
        });

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCaptureResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function saveComputopOrderDetails($responseTransfer, OrderTransfer $orderTransfer)
    {
        $this->logHeader($responseTransfer->getHeader(), self::METHOD);

        if (!$responseTransfer->getHeader()->getIsSuccess()) {
            return;
        }

        /** @var \Orm\Zed\Computop\Persistence\SpyPaymentComputop $paymentEntity */
        $paymentEntity = $this
            ->queryContainer
            ->queryPaymentByPayId($responseTransfer->getHeader()->getPayId())
            ->findOne();

        foreach ($orderTransfer->getItems() as $selectedItem) {
            foreach ($paymentEntity->getSpyPaymentComputopOrderItems() as $item) {
                if ($item->getFkSalesOrderItem() !== $selectedItem->getIdSalesOrderItem()) {
                    continue;
                }
                $item->setStatus($this->config->getOmsStatusRefunded());
                $item->save();
            }
        }

        $paymentEntityDetails = $paymentEntity->getSpyPaymentComputopDetail();
        $paymentEntityDetails->setAId($responseTransfer->getAId());
        $paymentEntityDetails->setAmount($responseTransfer->getAmount());
        $paymentEntityDetails->setCodeExt($responseTransfer->getCodeExt());
        $paymentEntityDetails->setErrorText($responseTransfer->getErrorText());
        $paymentEntityDetails->setTransactionId($responseTransfer->getTransactionId());
        $paymentEntityDetails->setRefNr($responseTransfer->getRefNr());
        $paymentEntityDetails->save();
    }
}
