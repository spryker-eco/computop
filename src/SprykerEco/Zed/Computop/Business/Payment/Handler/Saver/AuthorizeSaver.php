<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver;

use Generated\Shared\Transfer\ComputopApiAuthorizeResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class AuthorizeSaver extends AbstractSaver
{
    use TransactionTrait;

    /**
     * @var string
     */
    public const METHOD = 'AUTHORIZE';

    /**
     * @param \Generated\Shared\Transfer\ComputopApiAuthorizeResponseTransfer $responseTransfer
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
     * @param \Generated\Shared\Transfer\ComputopApiAuthorizeResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function saveComputopDetails(ComputopApiAuthorizeResponseTransfer $responseTransfer, OrderTransfer $orderTransfer): void
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
                $item->setStatus($this->config->getOmsStatusAuthorized());
                $item->save();
            }
        }

        $paymentEntityDetails = $paymentEntity->getSpyPaymentComputopDetail();
        $paymentEntityDetails->setRefNr($responseTransfer->getRefNr());
        $paymentEntityDetails->save();
    }
}
