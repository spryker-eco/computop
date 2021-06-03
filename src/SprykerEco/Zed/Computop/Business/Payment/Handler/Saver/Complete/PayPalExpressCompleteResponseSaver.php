<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Complete;

use Generated\Shared\Transfer\ComputopApiPayPalExpressCompleteResponseTransfer;
use Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsFacadeInterface;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface;

class PayPalExpressCompleteResponseSaver implements CompleteResponseSaverInterface
{
    use TransactionTrait;

    /**
     * @var \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface $queryContainer
     */
    protected $queryContainer;

    /**
     * @var \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @var \Orm\Zed\Computop\Persistence\SpyPaymentComputop
     */
    protected $paymentEntity;

    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsFacadeInterface $omsFacade
     * @param \SprykerEco\Zed\Computop\ComputopConfig $config
     */
    public function __construct(
        ComputopQueryContainerInterface $queryContainer,
        ComputopToOmsFacadeInterface $omsFacade,
        ComputopConfig $config
    )
    {
        $this->queryContainer = $queryContainer;
        $this->omsFacade = $omsFacade;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function save(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $payPalExpressCompleteResponseTransfer = $quoteTransfer
            ->getPayment()
            ->getComputopPayPalExpress()
            ->getPayPalExpressCompleteResponse();
        $this->setPaymentEntity($payPalExpressCompleteResponseTransfer->getHeader()->getTransId());
        $this->getTransactionHandler()->handleTransaction(
            function () use ($payPalExpressCompleteResponseTransfer) {
                $this->savePaymentComputopOrderItemsEntities($payPalExpressCompleteResponseTransfer);
            }
        );

        return $quoteTransfer;
    }

    /**
     * @param string $transactionId
     *
     * @return void
     */
    protected function setPaymentEntity(string $transactionId): void
    {
        $this->paymentEntity = $this->queryContainer->queryPaymentByTransactionId($transactionId)->findOne();
    }

    /**
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputop
     */
    protected function getPaymentEntity(): SpyPaymentComputop
    {
        return $this->paymentEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopEntity(ComputopPayPalExpressInitResponseTransfer $responseTransfer): void
    {
        $paymentEntity = $this->getPaymentEntity();
        $paymentEntity->setPayId($responseTransfer->getHeader()->getPayId());
        $paymentEntity->setXId($responseTransfer->getHeader()->getXId());
        $paymentEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function savePaymentComputopDetailEntity(ComputopPayPalExpressInitResponseTransfer $responseTransfer): void
    {
        $paymentEntityDetails = $this->getPaymentEntity()->getSpyPaymentComputopDetail();
        $paymentEntityDetails->fromArray($responseTransfer->toArray());
        $paymentEntityDetails->save();
    }

    /**
     * @return void
     */
    protected function savePaymentComputopOrderItemsEntities(ComputopApiPayPalExpressCompleteResponseTransfer $completeResponseTransfer): void
    {
        $orderItems = $this
            ->queryContainer
            ->getSpySalesOrderItemsById($this->getPaymentEntity()->getFkSalesOrder())
            ->find();

        foreach ($orderItems as $selectedItem) {
            foreach ($this->getPaymentEntity()->getSpyPaymentComputopOrderItems() as $item) {
                if ($item->getFkSalesOrderItem() !== $selectedItem->getIdSalesOrderItem()) {
                    continue;
                }
                $item->setStatus($this->config->getOmsStatusInitialized());
                $item->setIsPaymentConfirmed($completeResponseTransfer->getHeader()->getIsSuccess());
                $item->save();
            }
        }
    }
}
