<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Complete;

use Generated\Shared\Transfer\ComputopApiPayPalExpressCompleteResponseTransfer;
use Generated\Shared\Transfer\ComputopPaymentComputopOrderItemCollectionTransfer;
use Generated\Shared\Transfer\ComputopPaymentComputopTransfer;
use Generated\Shared\Transfer\ComputopSalesOrderItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface;
use SprykerEco\Zed\Computop\Persistence\ComputopRepositoryInterface;

class PayPalExpressCompleteResponseSaver implements PayPalExpressCompleteResponseSaverInterface
{
    use TransactionTrait;

    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $computopConfig;

    /**
     * @var \SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface
     */
    protected $computopEntityManager;

    /**
     * @var \SprykerEco\Zed\Computop\Persistence\ComputopRepositoryInterface
     */
    protected $computopRepository;

    /**
     * @param \SprykerEco\Zed\Computop\ComputopConfig $computopConfig
     * @param \SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface $computopEntityManager
     * @param \SprykerEco\Zed\Computop\Persistence\ComputopRepositoryInterface $computopRepository
     */
    public function __construct(
        ComputopConfig $computopConfig,
        ComputopEntityManagerInterface $computopEntityManager,
        ComputopRepositoryInterface $computopRepository
    ) {
        $this->computopConfig = $computopConfig;
        $this->computopEntityManager = $computopEntityManager;
        $this->computopRepository = $computopRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function save(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $payPalExpressCompleteResponseTransfer = $quoteTransfer->getPaymentOrFail()
            ->getComputopPayPalExpressOrFail()
            ->getPayPalExpressCompleteResponseOrFail();

        $computopPaymentTransfer = $this->computopRepository
            ->findComputopPaymentByComputopTransId(
                $payPalExpressCompleteResponseTransfer->getHeaderOrFail()->getTransIdOrFail(),
            );

        if ($computopPaymentTransfer === null) {
            return $quoteTransfer;
        }

        $this->getTransactionHandler()->handleTransaction(
            function () use ($payPalExpressCompleteResponseTransfer, $computopPaymentTransfer) {
                $this->executeSavePaymentComputopDataTransaction(
                    $payPalExpressCompleteResponseTransfer,
                    $computopPaymentTransfer,
                );
            },
        );

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopApiPayPalExpressCompleteResponseTransfer $payPalExpressCompleteResponseTransfer
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentTransfer
     *
     * @return void
     */
    protected function executeSavePaymentComputopDataTransaction(
        ComputopApiPayPalExpressCompleteResponseTransfer $payPalExpressCompleteResponseTransfer,
        ComputopPaymentComputopTransfer $computopPaymentTransfer
    ): void {
        $this->savePaymentComputopDetail($payPalExpressCompleteResponseTransfer, $computopPaymentTransfer);
        $this->savePaymentComputop($payPalExpressCompleteResponseTransfer, $computopPaymentTransfer);
        $this->savePaymentComputopOrderItems($payPalExpressCompleteResponseTransfer, $computopPaymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopApiPayPalExpressCompleteResponseTransfer $computopApiPayPalExpressCompleteResponseTransfer
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     *
     * @return void
     */
    protected function savePaymentComputop(
        ComputopApiPayPalExpressCompleteResponseTransfer $computopApiPayPalExpressCompleteResponseTransfer,
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
    ): void {
        $computopApiResponseHeaderTransfer = $computopApiPayPalExpressCompleteResponseTransfer->getHeaderOrFail();

        $computopPaymentComputopTransfer->setPayId($computopApiResponseHeaderTransfer->getPayId())
            ->setXId($computopApiResponseHeaderTransfer->getXId());

        $this->computopEntityManager->updateComputopPayment($computopPaymentComputopTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopApiPayPalExpressCompleteResponseTransfer $payPalExpressCompleteResponseTransfer
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     *
     * @return void
     */
    protected function savePaymentComputopDetail(
        ComputopApiPayPalExpressCompleteResponseTransfer $payPalExpressCompleteResponseTransfer,
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
    ): void {
        $computopPaymentComputopDetailTransfer = $this
            ->computopRepository
            ->findComputopPaymentDetail($computopPaymentComputopTransfer);

        if ($computopPaymentComputopDetailTransfer === null) {
            return;
        }

        $computopPaymentComputopDetailTransfer->fromArray($payPalExpressCompleteResponseTransfer->toArray(), true);

        $this->computopEntityManager->updateComputopPaymentDetail($computopPaymentComputopDetailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopApiPayPalExpressCompleteResponseTransfer $completeResponseTransfer
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     *
     * @return void
     */
    protected function savePaymentComputopOrderItems(
        ComputopApiPayPalExpressCompleteResponseTransfer $completeResponseTransfer,
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
    ): void {
        $computopSalesOrderItemCollectionTransfer = $this->computopRepository
            ->getComputopSalesOrderItemCollection($computopPaymentComputopTransfer);

        $computopPaymentComputopOrderItemCollectionTransfer = $this->computopRepository
            ->getComputopPaymentComputopOrderItemCollection($computopPaymentComputopTransfer);

        foreach ($computopSalesOrderItemCollectionTransfer->getComputopSalesOrderItems() as $computopSalesOrderItemTransfer) {
            $this->updatePaymentComputopOrderItem(
                $computopPaymentComputopOrderItemCollectionTransfer,
                $computopSalesOrderItemTransfer,
                $completeResponseTransfer,
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopOrderItemCollectionTransfer $computopPaymentComputopOrderItemsCollection
     * @param \Generated\Shared\Transfer\ComputopSalesOrderItemTransfer $computopSalesOrderItemTransfer
     * @param \Generated\Shared\Transfer\ComputopApiPayPalExpressCompleteResponseTransfer $completeResponseTransfer
     *
     * @return void
     */
    protected function updatePaymentComputopOrderItem(
        ComputopPaymentComputopOrderItemCollectionTransfer $computopPaymentComputopOrderItemsCollection,
        ComputopSalesOrderItemTransfer $computopSalesOrderItemTransfer,
        ComputopApiPayPalExpressCompleteResponseTransfer $completeResponseTransfer
    ): void {
        foreach ($computopPaymentComputopOrderItemsCollection->getComputopPaymentComputopOrderItems() as $computopPaymentComputopOrderItemTransfer) {
            if ($computopPaymentComputopOrderItemTransfer->getFkSalesOrderItem() !== $computopSalesOrderItemTransfer->getIdSalesOrderItem()) {
                continue;
            }

            $isPaymentConfirmed = $completeResponseTransfer->getHeaderOrFail()->getIsSuccess();
            $status = $isPaymentConfirmed ? $this->computopConfig->getOmsStatusAuthorized() : $this->computopConfig->getOmsStatusAuthorizationFailed();

            $computopPaymentComputopOrderItemTransfer->setStatus($status)
                ->setIsPaymentConfirmed($isPaymentConfirmed);

            $this->computopEntityManager->updateComputopPaymentComputopOrderItem($computopPaymentComputopOrderItemTransfer);
        }
    }
}
