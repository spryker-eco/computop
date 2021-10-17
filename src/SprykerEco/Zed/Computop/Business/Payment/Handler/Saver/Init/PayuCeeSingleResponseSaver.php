<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopPaymentComputopOrderItemCollectionTransfer;
use Generated\Shared\Transfer\ComputopPaymentComputopTransfer;
use Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer;
use Generated\Shared\Transfer\ComputopSalesOrderItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Shared\Computop\ComputopConfig as SharedComputopConfig;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface;
use SprykerEco\Zed\Computop\Persistence\ComputopRepositoryInterface;

class PayuCeeSingleResponseSaver implements InitResponseSaverInterface
{
    use TransactionTrait;

    /**
     * @var \SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface
     */
    protected $computopEntityManager;

    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $computopConfig;

    /**
     * @var \SprykerEco\Zed\Computop\Persistence\ComputopRepositoryInterface
     */
    protected $computopRepository;

    /**
     * @param \SprykerEco\Zed\Computop\ComputopConfig $computopConfig
     * @param \SprykerEco\Zed\Computop\Persistence\ComputopRepositoryInterface $computopRepository
     * @param \SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface $computopEntityManager
     */
    public function __construct(
        ComputopConfig $computopConfig,
        ComputopRepositoryInterface $computopRepository,
        ComputopEntityManagerInterface $computopEntityManager
    ) {
        $this->computopConfig = $computopConfig;
        $this->computopRepository = $computopRepository;
        $this->computopEntityManager = $computopEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function save(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $computopPayuCeeSingleInitResponseTransfer = $this->getPaymentTransferFromQuoteOrFail($quoteTransfer)
            ->getComputopPayuCeeSingleOrFail()
            ->getPayuCeeSingleInitResponseOrFail();

        $computopApiResponseHeaderTransfer = $computopPayuCeeSingleInitResponseTransfer->getHeaderOrFail()
            ->requireTransId()
            ->requirePayId()
            ->requireXid();

        if (!$computopApiResponseHeaderTransfer->getIsSuccess()) {
            return $quoteTransfer;
        }

        $computopPaymentComputopTransfer = $this->computopRepository->findComputopPaymentByComputopTransId(
            $computopApiResponseHeaderTransfer->getTransId()
        );
        if ($computopPaymentComputopTransfer === null) {
            return $quoteTransfer;
        }

        $this->getTransactionHandler()->handleTransaction(function () use ($computopPaymentComputopTransfer, $computopPayuCeeSingleInitResponseTransfer) {
            $this->executeSavePaymentResponseTransaction($computopPaymentComputopTransfer, $computopPayuCeeSingleInitResponseTransfer);
        });

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer
     *
     * @return void
     */
    protected function executeSavePaymentResponseTransaction(
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer,
        ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer
    ): void {
        $this->savePaymentComputopEntity($computopPaymentComputopTransfer, $computopPayuCeeSingleInitResponseTransfer->getHeader());
        $this->savePaymentComputopDetailEntity($computopPaymentComputopTransfer, $computopPayuCeeSingleInitResponseTransfer);
        $paymentStatus = $this->getOrderItemPaymentStatusFromResponseHeader($computopPayuCeeSingleInitResponseTransfer->getHeader());
        if ($paymentStatus) {
            $this->savePaymentComputopOrderItemsEntities($computopPaymentComputopTransfer, $paymentStatus);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function getPaymentTransferFromQuoteOrFail(QuoteTransfer $quoteTransfer): PaymentTransfer
    {
        foreach ($quoteTransfer->getPayments() as $paymentTransfer) {
            if ($paymentTransfer !== null) {
                return $paymentTransfer;
            }
        }

        return $quoteTransfer->getPaymentOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer
     *
     * @return void
     */
    protected function savePaymentComputopEntity(
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer,
        ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer
    ): void {
        $computopPaymentComputopTransfer
            ->setPayId($computopApiResponseHeaderTransfer->getPayId())
            ->setXId($computopApiResponseHeaderTransfer->getXId());

        $this->computopEntityManager->saveComputopPayment($computopPaymentComputopTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopDetailEntity(
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer,
        ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer
    ): void {
        $computopPaymentComputopDetailTransfer = $this->computopRepository->findComputopPaymentDetail($computopPaymentComputopTransfer);
        $computopPaymentComputopDetailTransfer->fromArray($computopPayuCeeSingleInitResponseTransfer->toArray(), true);
        $computopPaymentComputopDetailTransfer->setCustomerTransactionId(
            (int)$computopPaymentComputopDetailTransfer->getCustomerTransactionId()
        );

        $this->computopEntityManager->updateComputopPaymentDetail($computopPaymentComputopDetailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     * @param string $paymentStatus
     *
     * @return void
     */
    protected function savePaymentComputopOrderItemsEntities(
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer,
        string $paymentStatus
    ): void {
        $salesOrderItemsCollectionTransfer = $this->computopRepository
            ->getComputopSalesOrderItemsCollection($computopPaymentComputopTransfer);

        $computopPaymentComputopOrderItemsCollection = $this->computopRepository
            ->getComputopPaymentComputopOrderItemsCollection($computopPaymentComputopTransfer);

        foreach ($salesOrderItemsCollectionTransfer->getComputopSalesOrderItems() as $computopSalesOrderItem) {
            $this->updateComputopSalesOrderItem(
                $computopPaymentComputopOrderItemsCollection,
                $computopSalesOrderItem,
                $paymentStatus
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopOrderItemCollectionTransfer $computopPaymentComputopOrderItemsCollection
     * @param \Generated\Shared\Transfer\ComputopSalesOrderItemTransfer $computopSalesOrderItem
     * @param string|null $paymentStatus
     *
     * @return void
     */
    protected function updateComputopSalesOrderItem(
        ComputopPaymentComputopOrderItemCollectionTransfer $computopPaymentComputopOrderItemsCollection,
        ComputopSalesOrderItemTransfer $computopSalesOrderItem,
        ?string $paymentStatus
    ): void {
        foreach ($computopPaymentComputopOrderItemsCollection->getComputopPaymentComputopOrderItems() as $computopPaymentComputopOrderItem) {
            if ($computopSalesOrderItem->getIdSalesOrderItem() !== $computopPaymentComputopOrderItem->getFkSalesOrderItem()) {
                continue;
            }

            $computopPaymentComputopOrderItem->setStatus($paymentStatus);
            $this->computopEntityManager->updateComputopPaymentComputopOrderItem($computopPaymentComputopOrderItem);

            return;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer
     *
     * @return string
     */
    protected function getOrderItemPaymentStatusFromResponseHeader(
        ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer
    ): ?string {
        if ($computopApiResponseHeaderTransfer->getStatus() === SharedComputopConfig::AUTHORIZE_REQUEST_STATUS) {
            return $this->computopConfig->getAuthorizeRequestOmsStatus();
        }

        if ($computopApiResponseHeaderTransfer->getStatus() === SharedComputopConfig::SUCCESS_OK) {
            return $this->computopConfig->getOmsStatusAuthorized();
        }

        return null;
    }
}
