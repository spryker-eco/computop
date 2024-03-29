<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init;

use Generated\Shared\Transfer\ComputopPaymentComputopOrderItemCollectionTransfer;
use Generated\Shared\Transfer\ComputopPaymentComputopTransfer;
use Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer;
use Generated\Shared\Transfer\ComputopSalesOrderItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface;
use SprykerEco\Zed\Computop\Persistence\ComputopRepositoryInterface;

class PayPalExpressResponseSaver implements InitResponseSaverInterface
{
    use TransactionTrait;

    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $computopConfig;

    /**
     * @var \SprykerEco\Zed\Computop\Persistence\ComputopRepositoryInterface
     */
    protected $computopRepository;

    /**
     * @var \SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface
     */
    protected $computopEntityManager;

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
        $computopPayPalExpressInitResponseTransfer = $quoteTransfer->getPaymentOrFail()
            ->getComputopPayPalExpressOrFail()
            ->getPayPalExpressInitResponseOrFail();

        $computopPaymentComputopTransfer = $this->computopRepository
            ->findComputopPaymentByComputopTransId(
                $computopPayPalExpressInitResponseTransfer->getHeaderOrFail()->getTransIdOrFail(),
            );

        if ($computopPaymentComputopTransfer === null) {
            return $quoteTransfer;
        }

        if ($computopPayPalExpressInitResponseTransfer->getHeaderOrFail()->getIsSuccess()) {
            $this->getTransactionHandler()->handleTransaction(
                function () use ($computopPaymentComputopTransfer, $computopPayPalExpressInitResponseTransfer) {
                    $this->executeSavePaymentComputopDataTransaction($computopPaymentComputopTransfer, $computopPayPalExpressInitResponseTransfer);
                },
            );
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     * @param \Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
     *
     * @return void
     */
    protected function executeSavePaymentComputopDataTransaction(
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer,
        ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
    ): void {
        $this->savePaymentComputopEntity($computopPaymentComputopTransfer, $computopPayPalExpressInitResponseTransfer);
        $this->savePaymentComputopDetailEntity($computopPaymentComputopTransfer, $computopPayPalExpressInitResponseTransfer);
        $this->savePaymentComputopOrderItemEntities($computopPaymentComputopTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     * @param \Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopEntity(
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer,
        ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
    ): void {
        $computopPaymentComputopTransfer->setPayId($computopPayPalExpressInitResponseTransfer->getHeaderOrFail()->getPayId());
        $computopPaymentComputopTransfer->setXId($computopPayPalExpressInitResponseTransfer->getHeaderOrFail()->getXId());

        $this->computopEntityManager->updateComputopPayment($computopPaymentComputopTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     * @param \Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopDetailEntity(
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer,
        ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
    ): void {
        $computopPaymentComputopDetailTransfer = $this->computopRepository
            ->findComputopPaymentDetail($computopPaymentComputopTransfer);

        if ($computopPaymentComputopDetailTransfer === null) {
            return;
        }

        $computopPaymentComputopDetailTransfer->fromArray($computopPayPalExpressInitResponseTransfer->toArray(), true);

        $this->computopEntityManager->updateComputopPaymentDetail($computopPaymentComputopDetailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     *
     * @return void
     */
    protected function savePaymentComputopOrderItemEntities(
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
    ): void {
        $salesOrderItemCollectionTransfer = $this->computopRepository
            ->getComputopSalesOrderItemCollection($computopPaymentComputopTransfer);

        $computopPaymentComputopOrderItemCollection = $this->computopRepository
            ->getComputopPaymentComputopOrderItemCollection($computopPaymentComputopTransfer);

        foreach ($salesOrderItemCollectionTransfer->getComputopSalesOrderItems() as $computopSalesOrderItem) {
            $this->updateComputopSalesOrderItemCollection(
                $computopPaymentComputopOrderItemCollection,
                $computopSalesOrderItem,
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopOrderItemCollectionTransfer $computopPaymentComputopOrderItemCollection
     * @param \Generated\Shared\Transfer\ComputopSalesOrderItemTransfer $computopSalesOrderItem
     *
     * @return void
     */
    protected function updateComputopSalesOrderItemCollection(
        ComputopPaymentComputopOrderItemCollectionTransfer $computopPaymentComputopOrderItemCollection,
        ComputopSalesOrderItemTransfer $computopSalesOrderItem
    ): void {
        foreach ($computopPaymentComputopOrderItemCollection->getComputopPaymentComputopOrderItems() as $computopPaymentComputopOrderItem) {
            if ($computopSalesOrderItem->getIdSalesOrderItem() !== $computopPaymentComputopOrderItem->getFkSalesOrderItem()) {
                continue;
            }
            $computopPaymentComputopOrderItem->setStatus($this->computopConfig->getOmsStatusInitialized());
            $this->computopEntityManager->updateComputopPaymentComputopOrderItem($computopPaymentComputopOrderItem);
        }
    }
}
