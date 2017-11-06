<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler;

use Generated\Shared\Transfer\ComputopHeaderPaymentTransfer;
use Generated\Shared\Transfer\ComputopInquireResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class InquireHandler extends AbstractHandler
{
    use DatabaseTransactionHandlerTrait;

    const METHOD = 'INQUIRE';

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \Generated\Shared\Transfer\ComputopInquireResponseTransfer
     */
    public function handle(
        OrderTransfer $orderTransfer,
        ComputopHeaderPaymentTransfer $computopHeaderPayment
    ) {
        /** @var \Generated\Shared\Transfer\ComputopInquireResponseTransfer $responseTransfer */
        $responseTransfer = $this->request->request($orderTransfer, $computopHeaderPayment);

        $this->handleDatabaseTransaction(function () use ($responseTransfer, $orderTransfer) {
            $this->saveComputopOrderDetails($responseTransfer, $orderTransfer);
        });

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopInquireResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function saveComputopOrderDetails(ComputopInquireResponseTransfer $responseTransfer, OrderTransfer $orderTransfer)
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

        $paymentEntityDetails = $paymentEntity->getSpyPaymentComputopDetail();
        $paymentEntityDetails->setAmountAuth($responseTransfer->getAmountAuth());
        $paymentEntityDetails->setAmountCap($responseTransfer->getAmountCap());
        $paymentEntityDetails->setAmountCred($responseTransfer->getAmountCred());
        $paymentEntityDetails->save();
    }
}
