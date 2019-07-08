<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Order;

use ArrayObject;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Zed\Computop\Business\Exception\ComputopMethodMapperException;
use SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface;
use SprykerEco\Zed\Computop\ComputopConfig;

class OrderManager implements OrderManagerInterface
{
    use TransactionTrait;

    /**
     * @var \SprykerEco\Zed\Computop\Business\Order\OrderManagerInterface
     */
    protected $mappers;

    /**
     * @var \SprykerEco\Zed\Computop\Business\Order\OrderManagerInterface
     */
    protected $activeMapper;

    /**
     * @var \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected $computopTransfer;

    /**
     * @var \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected $computopResponseTransfer;

    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $config;

    /**
     * @var string
     */
    protected $methodName;

    /**
     * @param \SprykerEco\Zed\Computop\ComputopConfig $config
     */
    public function __construct(ComputopConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface $mapper
     *
     * @return void
     */
    public function registerMapper(MapperInterface $mapper)
    {
        $this->mappers[$mapper->getMethodName()] = $mapper;
    }

    /**
     * @param string $methodName
     *
     * @throws \SprykerEco\Zed\Computop\Business\Exception\ComputopMethodMapperException
     *
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    protected function getMethodMapper($methodName)
    {
        if (isset($this->mappers[$methodName]) === false) {
            throw new ComputopMethodMapperException('The method mapper is not registered.');
        }

        return $this->mappers[$methodName];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        if ($quoteTransfer->getPayment()->getPaymentProvider() !== ComputopSharedConfig::PROVIDER_NAME) {
            return;
        }

        $this->methodName = $quoteTransfer->getPayment()->getPaymentMethod();
        $this->activeMapper = $this->getMethodMapper($quoteTransfer->getPayment()->getPaymentMethod());
        $this->computopTransfer = $this->activeMapper->getComputopTransfer($quoteTransfer->getPayment());
        $this->computopResponseTransfer = $this->activeMapper->getComputopResponseTransfer($quoteTransfer->getPayment());

        $paymentEntity = $this->savePaymentForOrder(
            $quoteTransfer->getPayment(),
            $saveOrderTransfer
        );

        $this->savePaymentDetailForOrder(
            $quoteTransfer->getPayment(),
            $paymentEntity
        );

        $this->savePaymentForOrderItems(
            $saveOrderTransfer->getOrderItems(),
            $paymentEntity->getIdPaymentComputop()
        );

        $this->saveExpenses($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputop
     */
    protected function savePaymentForOrder(PaymentTransfer $paymentTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        $paymentEntity = new SpyPaymentComputop();

        $paymentEntity->setClientIp($this->computopTransfer->getClientIp());
        $paymentEntity->setPaymentMethod($paymentTransfer->getPaymentMethod());
        $paymentEntity->setReference($saveOrderTransfer->getOrderReference());
        $paymentEntity->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder());
        $paymentEntity->setTransId($this->computopTransfer->getTransId());
        $paymentEntity->setPayId($this->computopTransfer->getPayId());
        $paymentEntity->setReqId($this->computopTransfer->getReqId());

        if ($this->isPaymentMethodEasyCredit()) {
            $paymentEntity->setXId($this->computopResponseTransfer->getHeader()->getXId());
        }

        $paymentEntity->save();

        return $paymentEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop $paymentEntity
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail
     */
    protected function savePaymentDetailForOrder(PaymentTransfer $paymentTransfer, SpyPaymentComputop $paymentEntity)
    {
        $paymentDetailEntity = new SpyPaymentComputopDetail();

        $paymentDetailEntity->fromArray($this->activeMapper->getPaymentDetailsArray($paymentTransfer));
        $paymentDetailEntity->setIdPaymentComputop($paymentEntity->getIdPaymentComputop());

        if ($this->isPaymentMethodEasyCredit()) {
            $paymentDetailEntity->fromArray($this->computopResponseTransfer->toArray());
        }

        $paymentDetailEntity->save();

        return $paymentDetailEntity;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $orderItemTransfers
     * @param int $idPayment
     *
     * @return void
     */
    protected function savePaymentForOrderItems(ArrayObject $orderItemTransfers, $idPayment)
    {
        foreach ($orderItemTransfers as $orderItemTransfer) {
            $paymentOrderItemEntity = new SpyPaymentComputopOrderItem();

            $paymentOrderItemEntity
                ->setFkPaymentComputop($idPayment)
                ->setFkSalesOrderItem($orderItemTransfer->getIdSalesOrderItem());
            $paymentOrderItemEntity->setStatus($this->config->getOmsStatusNew());

            if ($this->isPaymentMethodEasyCredit()) {
                $paymentOrderItemEntity->setStatus($this->config->getOmsStatusInitialized());
            }

            $paymentOrderItemEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function saveExpenses(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() === $this->config->getComputopEasyCreditExpenseType()) {
                $salesOrderExpenseEntity = $this->createExpenseEntity($expenseTransfer);
                $salesOrderExpenseEntity = $this->expandExpenseWithPrices($salesOrderExpenseEntity, $expenseTransfer);

                $salesOrderExpenseEntity->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder());
                $salesOrderExpenseEntity->save();

                $saveOrderTransfer->addOrderExpense(
                    $this->createOrderExpenseTransfer($expenseTransfer, $salesOrderExpenseEntity)
                );
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpense
     */
    protected function createExpenseEntity(ExpenseTransfer $expenseTransfer): SpySalesExpense
    {
        $salesOrderExpenseEntity = new SpySalesExpense();
        $salesOrderExpenseEntity->fromArray(
            $expenseTransfer->toArray()
        );

        return $salesOrderExpenseEntity;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpense $salesOrderExpenseEntity
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpense
     */
    protected function expandExpenseWithPrices(
        SpySalesExpense $salesOrderExpenseEntity,
        ExpenseTransfer $expenseTransfer
    ): SpySalesExpense {
        $salesOrderExpenseEntity->setGrossPrice($expenseTransfer->getSumGrossPrice());
        $salesOrderExpenseEntity->setNetPrice($expenseTransfer->getSumNetPrice());
        $salesOrderExpenseEntity->setPrice($expenseTransfer->getSumPrice());
        $salesOrderExpenseEntity->setTaxAmount($expenseTransfer->getSumTaxAmount());
        $salesOrderExpenseEntity->setDiscountAmountAggregation($expenseTransfer->getSumDiscountAmountAggregation());
        $salesOrderExpenseEntity->setPriceToPayAggregation($expenseTransfer->getSumPriceToPayAggregation());

        return $salesOrderExpenseEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpense $salesOrderExpenseEntity
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createOrderExpenseTransfer(
        ExpenseTransfer $expenseTransfer,
        SpySalesExpense $salesOrderExpenseEntity
    ): ExpenseTransfer {
        $orderExpense = clone $expenseTransfer;
        $orderExpense->setFkSalesOrder($salesOrderExpenseEntity->getFkSalesOrder());
        $orderExpense->setIdSalesExpense($salesOrderExpenseEntity->getIdSalesExpense());

        return $orderExpense;
    }

    /**
     * @return bool
     */
    protected function isPaymentMethodEasyCredit(): bool
    {
        return $this->methodName === ComputopSharedConfig::PAYMENT_METHOD_EASY_CREDIT;
    }
}
