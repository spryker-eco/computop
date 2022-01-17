<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Order;

use ArrayObject;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Zed\Computop\Business\Exception\ComputopMethodMapperException;
use SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface;
use SprykerEco\Zed\Computop\ComputopConfig;

class OrderManager implements OrderManagerInterface
{
    use TransactionTrait;

    /**
     * @var array<\SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface>
     */
    protected $mappers;

    /**
     * @var \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    protected $activeMapper;

    /**
     * @var \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected $computopTransfer;

    /**
     * @var \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\ComputopDirectDebitInitResponseTransfer
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
    public function registerMapper(MapperInterface $mapper): void
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
    protected function getMethodMapper(string $methodName): MapperInterface
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
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $paymentTransfer = $quoteTransfer->getPaymentOrFail();

        if ($paymentTransfer->getPaymentProvider() !== ComputopSharedConfig::PROVIDER_NAME) {
            return;
        }

        $this->methodName = (string)$paymentTransfer->getPaymentMethod();
        $this->activeMapper = $this->getMethodMapper($paymentTransfer->getPaymentMethodOrFail());
        $this->computopTransfer = $this->activeMapper->getComputopTransfer($paymentTransfer);
        $this->computopResponseTransfer = $this->activeMapper->getComputopResponseTransfer($paymentTransfer);

        $paymentEntity = $this->savePaymentForOrder(
            $paymentTransfer,
            $saveOrderTransfer,
        );

        $this->savePaymentDetailForOrder(
            $paymentTransfer,
            $paymentEntity,
        );

        $this->savePaymentForOrderItems(
            $saveOrderTransfer->getOrderItems(),
            $paymentEntity->getIdPaymentComputop(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputop
     */
    protected function savePaymentForOrder(
        PaymentTransfer $paymentTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): SpyPaymentComputop {
        $paymentEntity = new SpyPaymentComputop();

        /** @var \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopTransfer */
        $computopTransfer = $this->computopTransfer;
        $paymentEntity->setClientIp((string)$computopTransfer->getClientIp());
        $paymentEntity->setPaymentMethod((string)$paymentTransfer->getPaymentMethod());
        $paymentEntity->setReference((string)$saveOrderTransfer->getOrderReference());
        $paymentEntity->setFkSalesOrder((int)$saveOrderTransfer->getIdSalesOrder());
        $paymentEntity->setTransId($computopTransfer->getTransId());
        $paymentEntity->setPayId($computopTransfer->getPayId());
        $paymentEntity->setReqId($computopTransfer->getReqId());

        if ($this->isPaymentMethodEasyCredit()) {
            $paymentEntity->setXId($computopTransfer->getHeaderOrFail()->getXId());
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
    protected function savePaymentDetailForOrder(
        PaymentTransfer $paymentTransfer,
        SpyPaymentComputop $paymentEntity
    ): SpyPaymentComputopDetail {
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
     * @param \ArrayObject<\Generated\Shared\Transfer\ItemTransfer> $orderItemTransfers
     * @param int $idPayment
     *
     * @return void
     */
    protected function savePaymentForOrderItems(ArrayObject $orderItemTransfers, int $idPayment): void
    {
        foreach ($orderItemTransfers as $orderItemTransfer) {
            $paymentOrderItemEntity = new SpyPaymentComputopOrderItem();

            $paymentOrderItemEntity
                ->setFkPaymentComputop($idPayment)
                ->setFkSalesOrderItem($orderItemTransfer->getIdSalesOrderItemOrFail());
            $paymentOrderItemEntity->setStatus($this->config->getOmsStatusNew());

            if ($this->isPaymentMethodEasyCredit()) {
                $paymentOrderItemEntity->setStatus($this->config->getOmsStatusInitialized());
            }

            $paymentOrderItemEntity->save();
        }
    }

    /**
     * @return bool
     */
    protected function isPaymentMethodEasyCredit(): bool
    {
        return $this->methodName === ComputopSharedConfig::PAYMENT_METHOD_EASY_CREDIT;
    }
}
