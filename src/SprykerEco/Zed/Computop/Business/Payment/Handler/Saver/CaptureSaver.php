<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver;

use Generated\Shared\Transfer\ComputopApiCaptureResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CaptureSaver extends AbstractSaver
{
    use TransactionTrait;

    public const METHOD = 'CAPTURE';

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function save(TransferInterface $responseTransfer, OrderTransfer $orderTransfer): TransferInterface
    {
        $this->getTransactionHandler()->handleTransaction(
            function () use ($responseTransfer, $orderTransfer): void {
                /** @var \Generated\Shared\Transfer\ComputopApiCaptureResponseTransfer $responseTransfer */
                $this->saveComputopDetails($responseTransfer, $orderTransfer);
            }
        );

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopApiCaptureResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function saveComputopDetails(ComputopApiCaptureResponseTransfer $responseTransfer, OrderTransfer $orderTransfer): void
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
                $item->setStatus($this->config->getOmsStatusCaptured());
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
