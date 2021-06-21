<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Complete;

use Closure;
use Generated\Shared\Transfer\ComputopApiPayPalExpressCompleteResponseTransfer;
use Generated\Shared\Transfer\ComputopPaymentComputopTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsFacadeInterface;
use SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface;
use SprykerEco\Zed\Computop\Persistence\ComputopRepositoryInterface;

class PayPalExpressCompleteResponseSaver implements CompleteResponseSaverInterface
{
    use TransactionTrait;

    /**
     * @var \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsFacadeInterface
     */
    protected $omsFacade;

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
     * @param \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsFacadeInterface $omsFacade
     * @param \SprykerEco\Zed\Computop\ComputopConfig $computopConfig
     * @param \SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface $computopEntityManager
     * @param \SprykerEco\Zed\Computop\Persistence\ComputopRepositoryInterface $computopRepository
     */
    public function __construct(
        ComputopToOmsFacadeInterface $omsFacade,
        ComputopConfig $computopConfig,
        ComputopEntityManagerInterface $computopEntityManager,
        ComputopRepositoryInterface $computopRepository
    ) {
        $this->omsFacade = $omsFacade;
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
        $payPalExpressCompleteResponseTransfer = $quoteTransfer->getPayment()
            ->getComputopPayPalExpress()
            ->getPayPalExpressCompleteResponse();

        $computopPaymentTransfer = $this->
        computopRepository
            ->getComputopPaymentByComputopTransId(
                $payPalExpressCompleteResponseTransfer->getHeader()->getTransId()
            );

        $this->getTransactionHandler()->handleTransaction(
            $this->executeSavePaymentComputopDataTransaction(
                $payPalExpressCompleteResponseTransfer,
                $computopPaymentTransfer
            )
        );

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopApiPayPalExpressCompleteResponseTransfer $payPalExpressCompleteResponseTransfer
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentTransfer
     *
     * @return \Closure
     */
    protected function executeSavePaymentComputopDataTransaction(
        ComputopApiPayPalExpressCompleteResponseTransfer $payPalExpressCompleteResponseTransfer,
        ComputopPaymentComputopTransfer $computopPaymentTransfer
    ): Closure {
        return function () use ($payPalExpressCompleteResponseTransfer, $computopPaymentTransfer) {
            $this->savePaymentComputopDetail($payPalExpressCompleteResponseTransfer, $computopPaymentTransfer);
            $this->savePaymentComputop($payPalExpressCompleteResponseTransfer, $computopPaymentTransfer);
            $this->savePaymentComputopOrderItems($payPalExpressCompleteResponseTransfer, $computopPaymentTransfer);
        };
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopApiPayPalExpressCompleteResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
     *
     * @return void
     */
    protected function savePaymentComputop(
        ComputopApiPayPalExpressCompleteResponseTransfer $responseTransfer,
        ComputopPaymentComputopTransfer $computopPaymentComputopTransfer
    ): void {
        $this->computopEntityManager->updateComputopPayment($responseTransfer, $computopPaymentComputopTransfer);
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
        $this->computopEntityManager->updateComputopPaymentDetail($payPalExpressCompleteResponseTransfer, $computopPaymentComputopTransfer);
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
        $salesOrderItemsCollection = $this->computopRepository
            ->getComputopSalesOrderItemsCollection($computopPaymentComputopTransfer);

        $computopPaymentComputopOrderItemsCollection = $this->computopRepository
            ->getComputopPaymentComputopOrderItemsCollection($computopPaymentComputopTransfer);

        foreach ($salesOrderItemsCollection->getComputopSalesOrderItems() as $computopSalesOrderItemTransfer) {
            foreach ($computopPaymentComputopOrderItemsCollection->getComputopPaymentComputopOrderItems() as $computopPaymentComputopOrderItemTransfer) {
                if ($computopPaymentComputopOrderItemTransfer->getFkSalesOrderItem() !== $computopSalesOrderItemTransfer->getIdSalesOrderItem()) {
                    continue;
                }
                $computopPaymentComputopOrderItemTransfer->setStatus($this->computopConfig->getOmsStatusInitialized());
                $computopPaymentComputopOrderItemTransfer->setIsPaymentConfirmed($completeResponseTransfer->getHeader()->getIsSuccess());

                $this->computopEntityManager->updateComputopPaymentComputopOrderItem($computopPaymentComputopOrderItemTransfer);
            }
        }
    }
}
