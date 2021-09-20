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
        $computopPayPalExpressInitResponseTransfer = $quoteTransfer->getPayment()
            ->getComputopPayPalExpress()
            ->getPayPalExpressInitResponse();

        $computopPaymentComputopTransfer = $this->computopRepository
            ->getComputopPaymentByComputopTransId(
                $computopPayPalExpressInitResponseTransfer->getHeader()->getTransId()
            );

        if ($computopPayPalExpressInitResponseTransfer->getHeader()->getIsSuccess()) {
            $this->getTransactionHandler()->handleTransaction(
                function () use ($computopPaymentComputopTransfer, $computopPayPalExpressInitResponseTransfer) {
                    $this->executeSavePaymentComputopDataTransaction($computopPaymentComputopTransfer, $computopPayPalExpressInitResponseTransfer);
                }
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
        $this->savePaymentComputopOrderItemsEntities($computopPaymentComputopTransfer);
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
        $computopPaymentComputopTransfer->setPayId($computopPayPalExpressInitResponseTransfer->getHeader()->getPayId());
        $computopPaymentComputopTransfer->setXId($computopPayPalExpressInitResponseTransfer->getHeader()->getXId());

        $this->computopEntityManager->saveComputopPayment($computopPaymentComputopTransfer);
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
            ->getComputopPaymentDetail($computopPaymentComputopTransfer);

        $computopPaymentComputopDetailTransfer->fromArray($computopPayPalExpressInitResponseTransfer->toArray(), true);

        $this->computopEntityManager->updateComputopPaymentDetail($computopPaymentComputopDetailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     *
     * @return void
     */
    protected function savePaymentComputopOrderItemsEntities(
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
    ): void {
        $salesOrderItemsCollectionTransfer = $this->computopRepository
            ->getComputopSalesOrderItemsCollection($computopPaymentComputopTransfer);

        $computopPaymentComputopOrderItemsCollection = $this->computopRepository
            ->getComputopPaymentComputopOrderItemsCollection($computopPaymentComputopTransfer);

        foreach ($salesOrderItemsCollectionTransfer->getComputopSalesOrderItems() as $computopSalesOrderItem) {
            $this->updateComputopSalesOrderItem(
                $computopPaymentComputopOrderItemsCollection,
                $computopSalesOrderItem
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopOrderItemCollectionTransfer $computopPaymentComputopOrderItemsCollection
     * @param \Generated\Shared\Transfer\ComputopSalesOrderItemTransfer $computopSalesOrderItem
     *
     * @return void
     */
    protected function updateComputopSalesOrderItem(
        ComputopPaymentComputopOrderItemCollectionTransfer $computopPaymentComputopOrderItemsCollection,
        ComputopSalesOrderItemTransfer $computopSalesOrderItem
    ): void {
        foreach ($computopPaymentComputopOrderItemsCollection->getComputopPaymentComputopOrderItems() as $computopPaymentComputopOrderItem) {
            if ($computopSalesOrderItem->getIdSalesOrderItem() !== $computopPaymentComputopOrderItem->getFkSalesOrderItem()) {
                continue;
            }
            $computopPaymentComputopOrderItem->setStatus($this->computopConfig->getOmsStatusInitialized());
            $this->computopEntityManager->updateComputopPaymentComputopOrderItem($computopPaymentComputopOrderItem);
        }
    }
}
