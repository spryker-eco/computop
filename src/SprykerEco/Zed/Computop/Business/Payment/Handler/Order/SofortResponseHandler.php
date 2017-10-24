<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Order;

use Generated\Shared\Transfer\ComputopSofortOrderResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsInterface;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface;

class SofortResponseHandler implements OrderResponseHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface $queryContainer
     */
    protected $queryContainer;

    /**
     * @var \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsInterface
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
     * @param \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsInterface $omsFacade
     * @param \SprykerEco\Zed\Computop\ComputopConfig $config
     */
    public function __construct(
        ComputopQueryContainerInterface $queryContainer,
        ComputopToOmsInterface $omsFacade,
        ComputopConfig $config
    ) {
        $this->queryContainer = $queryContainer;
        $this->omsFacade = $omsFacade;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function handle(QuoteTransfer $quoteTransfer)
    {
        $responseTransfer = $quoteTransfer->getPayment()->getComputopSofort()->getSofortOrderResponse();

        $this->handleDatabaseTransaction(function () use ($responseTransfer) {
            $this->saveComputopOrderDetails($responseTransfer);
            $this->triggerEvent($this->getPaymentEntity($responseTransfer));
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopSofortOrderResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function saveComputopOrderDetails(ComputopSofortOrderResponseTransfer $responseTransfer)
    {
        if (!$responseTransfer->getHeader()->getIsSuccess()) {
            return;
        }

        /** @var \Orm\Zed\Computop\Persistence\SpyPaymentComputop $paymentEntity */
        $paymentEntity = $this->getPaymentEntity($responseTransfer);

        $paymentEntity->setPayId($responseTransfer->getHeader()->getPayId());
        $paymentEntity->setXid($responseTransfer->getHeader()->getXId());
        $paymentEntity->save();

        foreach ($paymentEntity->getSpyPaymentComputopOrderItems() as $item) {
            //Sofort sets captured status on first call
            $item->setStatus($this->config->getOmsStatusCaptured());
            $item->save();
        }

        $paymentEntityDetails = $paymentEntity->getSpyPaymentComputopDetail();
        $paymentEntityDetails->setAccountOwner($responseTransfer->getAccountOwner());
        $paymentEntityDetails->setAccountBank($responseTransfer->getAccountBank());
        $paymentEntityDetails->setBankAccountBic($responseTransfer->getBankAccountBic());
        $paymentEntityDetails->setBankAccountIban($responseTransfer->getBankAccountIban());
        $paymentEntityDetails->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopSofortOrderResponseTransfer $responseTransfer
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputop
     */
    protected function getPaymentEntity(ComputopSofortOrderResponseTransfer $responseTransfer)
    {
        if (!isset($this->paymentEntity)) {
            $this->paymentEntity = $this
                ->queryContainer
                ->queryPaymentByTransactionId($responseTransfer->getHeader()->getTransId())
                ->findOne();
        }

        return $this->paymentEntity;
    }

    /**
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop $paymentEntity $paymentEntity
     *
     * @return void
     */
    protected function triggerEvent($paymentEntity)
    {
        $orderItems = SpySalesOrderItemQuery::create()
            ->filterByFkSalesOrder($paymentEntity->getFkSalesOrder())
            ->find();

        $this->omsFacade->triggerEvent(
            $this->config->getOmsCaptureEventName(),
            $orderItems,
            []
        );
    }
}
