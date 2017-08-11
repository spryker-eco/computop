<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler;

use Generated\Shared\Transfer\ComputopCreditCardReverseResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use SprykerEco\Shared\Computop\ComputopConstants;

class ReverseResponseHandler extends AbstractResponseHandler
{

    use DatabaseTransactionHandlerTrait;

    const METHOD = 'REVERSE';

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardReverseResponseTransfer $responseTransfer
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
     * @param \Generated\Shared\Transfer\ComputopCreditCardReverseResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function saveComputopOrderDetails(ComputopCreditCardReverseResponseTransfer $responseTransfer, OrderTransfer $orderTransfer)
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

        foreach ($orderTransfer->getItems() as $selectedItem) {
            foreach ($paymentEntity->getSpyPaymentComputopOrderItems() as $item) {
                if ($item->getFkSalesOrderItem() === $selectedItem->getIdSalesOrderItem()) {
                    $item->setStatus(ComputopConstants::COMPUTOP_OMS_STATUS_CANCELLED);
                    $item->save();
                }
            }
        }
    }

}
