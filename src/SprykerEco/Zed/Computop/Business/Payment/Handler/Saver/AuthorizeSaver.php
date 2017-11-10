<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver;

use Generated\Shared\Transfer\ComputopAuthorizeResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class AuthorizeSaver extends AbstractSaver
{
    use DatabaseTransactionHandlerTrait;

    const METHOD = 'AUTHORIZE';

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function save(TransferInterface $responseTransfer, OrderTransfer $orderTransfer)
    {
        $this->handleDatabaseTransaction(function () use ($responseTransfer, $orderTransfer) {
            $this->saveComputopDetails($responseTransfer, $orderTransfer);
        });

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopAuthorizeResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function saveComputopDetails(ComputopAuthorizeResponseTransfer $responseTransfer, OrderTransfer $orderTransfer)
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
                $item->setStatus($this->config->getOmsStatusAuthorized());
                $item->save();
            }
        }

        $paymentEntityDetails = $paymentEntity->getSpyPaymentComputopDetail();
        $paymentEntityDetails->setRefNr($responseTransfer->getRefNr());
        $paymentEntityDetails->save();
    }
}
