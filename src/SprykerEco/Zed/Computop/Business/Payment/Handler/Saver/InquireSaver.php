<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver;

use Generated\Shared\Transfer\ComputopApiInquireResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class InquireSaver extends AbstractSaver
{
    use TransactionTrait;

    /**
     * @var string
     */
    public const METHOD = 'INQUIRE';

    /**
     * @param \Generated\Shared\Transfer\ComputopApiInquireResponseTransfer $responseTransfer
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
     * @param \Generated\Shared\Transfer\ComputopApiInquireResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function saveComputopDetails(ComputopApiInquireResponseTransfer $responseTransfer, OrderTransfer $orderTransfer): void
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

        $paymentEntityDetails = $paymentEntity->getSpyPaymentComputopDetail();
        $paymentEntityDetails->setAmountAuth($responseTransfer->getAmountAuth());
        $paymentEntityDetails->setAmountCap($responseTransfer->getAmountCap());
        $paymentEntityDetails->setAmountCred($responseTransfer->getAmountCred());
        $paymentEntityDetails->save();
    }
}
