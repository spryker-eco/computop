<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Order;

use ArrayObject;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\Business\Exception\ComputopMethodMapperException;
use SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface;
use SprykerEco\Zed\Computop\ComputopConfig;

class OrderManager implements OrderManagerInterface
{

    use DatabaseTransactionHandlerTrait;

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
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        if ($quoteTransfer->getPayment()->getPaymentProvider() !== ComputopConstants::PROVIDER_NAME) {
            return;
        }

        $this->activeMapper = $this->getMethodMapper($quoteTransfer->getPayment()->getPaymentMethod());
        $this->computopTransfer = $this->activeMapper->getComputopTransfer($quoteTransfer->getPayment());
        $this->computopResponseTransfer = $this->activeMapper->getComputopResponseTransfer($quoteTransfer->getPayment());

        $this->handleDatabaseTransaction(function () use ($quoteTransfer, $checkoutResponseTransfer) {

            $paymentEntity = $this->savePaymentForOrder(
                $quoteTransfer->getPayment(),
                $checkoutResponseTransfer->getSaveOrder()
            );

            $this->savePaymentDetailForOrder(
                $quoteTransfer->getPayment(),
                $paymentEntity
            );

            $this->savePaymentForOrderItems(
                $checkoutResponseTransfer->getSaveOrder()->getOrderItems(),
                $paymentEntity->getIdPaymentComputop()
            );
        });
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

        if (!$this->config->isNeededRedirectAfterPlaceOrder($paymentTransfer->getPaymentSelection())) {
            $paymentEntity->setXId($this->computopResponseTransfer->getHeader()->getXId());
            $paymentEntity->setPayId($this->computopResponseTransfer->getHeader()->getPayId());
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

        $paymentDetailEntity->fromArray($this->activeMapper->getPaymentDetailForOrderArray($paymentTransfer));
        $paymentDetailEntity->setIdPaymentComputop($paymentEntity->getIdPaymentComputop());

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
            $paymentOrderItemEntity->setStatus(ComputopConstants::COMPUTOP_OMS_STATUS_NEW);

            $paymentOrderItemEntity->save();
        }
    }

}
