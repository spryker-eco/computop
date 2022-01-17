<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver;

use Generated\Shared\Transfer\ComputopApiRefundResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class RefundSaver extends AbstractSaver
{
    use TransactionTrait;

    /**
     * @var string
     */
    public const METHOD = 'REFUND';

    /**
     * @param \Generated\Shared\Transfer\ComputopApiRefundResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function save(TransferInterface $responseTransfer, OrderTransfer $orderTransfer): TransferInterface
    {
        $this->getTransactionHandler()->handleTransaction(
            function () use ($responseTransfer, $orderTransfer): void {
                $this->saveComputopDetails($responseTransfer, $orderTransfer);
            },
        );

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopApiRefundResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function saveComputopDetails(ComputopApiRefundResponseTransfer $responseTransfer, OrderTransfer $orderTransfer): void
    {
        $computopApiResponseHeaderTransfer = $responseTransfer->getHeaderOrFail();
        $this->logHeader($computopApiResponseHeaderTransfer, static::METHOD);

        if (!$computopApiResponseHeaderTransfer->getIsSuccess()) {
            return;
        }

        /** @var \Orm\Zed\Computop\Persistence\SpyPaymentComputop $paymentEntity */
        $paymentEntity = $this
            ->queryContainer
            ->queryPaymentByPayId($computopApiResponseHeaderTransfer->getPayIdOrFail())
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

        $paymentEntity->getSpyPaymentComputopDetail()
            ->setAId($responseTransfer->getAId())
            ->setAmount($responseTransfer->getAmount())
            ->setCodeExt($responseTransfer->getCodeExt())
            ->setErrorText($responseTransfer->getErrorText())
            ->setTransactionId($responseTransfer->getTransactionId())
            ->setRefNr($responseTransfer->getRefNr())
            ->save();
    }
}
