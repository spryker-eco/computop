<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler;

use Generated\Shared\Transfer\ComputopCreditCardInquireResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class InquireResponseHandler extends AbstractResponseHandler
{

    use DatabaseTransactionHandlerTrait;

    const METHOD = 'INQUIRE';

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardInquireResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function handle(
        $responseTransfer,
        OrderTransfer $orderTransfer
    ) {
        $this->handleDatabaseTransaction(function () use ($responseTransfer, $orderTransfer) {
            $this->saveComputopOrderDetails($responseTransfer, $orderTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardInquireResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function saveComputopOrderDetails(ComputopCreditCardInquireResponseTransfer $responseTransfer, OrderTransfer $orderTransfer)
    {
        $this->logHeader($responseTransfer->getHeader(), self::METHOD);

        if (!$responseTransfer->getHeader()->getIsSuccess()) {
            return;
        }

        /** @var \Orm\Zed\Computop\Persistence\SpyPaymentComputop $paymentEntity */
        $paymentEntity = $this
            ->queryContainer
            ->queryPaymentByPayId($orderTransfer->getComputopCreditCard()->getPayId())
            ->findOne();

        $paymentEntityDetails = $paymentEntity->getSpyPaymentComputopDetail();
        $paymentEntityDetails->setAmountAuth($responseTransfer->getAmountAuth());
        $paymentEntityDetails->setAmountCap($responseTransfer->getAmountCap());
        $paymentEntityDetails->setAmountCred($responseTransfer->getAmountCred());
        $paymentEntityDetails->save();
    }

}
