<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init;

use Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer;
use Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsFacadeInterface;
use SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface;

class PayuCeeSingleResponseSaver extends AbstractResponseSaver
{
    /**
     * @var \SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface
     */
    protected $computopEntityManager;

    /**
     * @param \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsFacadeInterface $omsFacade
     * @param \SprykerEco\Zed\Computop\ComputopConfig $config
     * @param \SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface $computopEntityManager
     */
    public function __construct(
        ComputopQueryContainerInterface $queryContainer,
        ComputopToOmsFacadeInterface $omsFacade,
        ComputopConfig $config,
        ComputopEntityManagerInterface $computopEntityManager
    ) {
        parent::__construct($queryContainer, $omsFacade, $config);
        $this->computopEntityManager = $computopEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function save(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $computopPayuCeeSingleInitResponse = $this->getPayuCeeSingleInitResponseFromQuoteTransfer($quoteTransfer);
        if (!$computopPayuCeeSingleInitResponse) {
            return $quoteTransfer;
        }

        $computopApiResponseHeaderTransfer = $computopPayuCeeSingleInitResponse->getHeaderOrFail()
            ->requireTransId()
            ->requirePayId()
            ->requireXid();

        if (!$computopApiResponseHeaderTransfer->getIsSuccess()) {
            return $quoteTransfer;
        }

        $this->setPaymentEntity($computopApiResponseHeaderTransfer->getTransId());
        if ($this->getPaymentEntity() === null) {
            return $quoteTransfer;
        }

        $this->handleSaveTransaction($computopPayuCeeSingleInitResponse);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer
     *
     * @return void
     */
    protected function handleSaveTransaction(
        ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer
    ): void {
        $this->getTransactionHandler()->handleTransaction(function () use ($computopPayuCeeSingleInitResponseTransfer) {
            $this->executeSavePaymentResponseTransaction($computopPayuCeeSingleInitResponseTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer
     *
     * @return void
     */
    protected function executeSavePaymentResponseTransaction(
        ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer
    ): void {
        $this->computopEntityManager->updatePaymentComputopEntityByComputopApiResponseHeaderTransfer(
            $computopPayuCeeSingleInitResponseTransfer->getHeader(),
            $this->getPaymentEntity()
        );

        if ($this->getPaymentEntity()->getSpyPaymentComputopDetail()) {
            $this->computopEntityManager->updatePaymentComputopDetailEntityByComputopApiResponseHeaderTransfer(
                $this->getPaymentEntity()->getSpyPaymentComputopDetail(),
                $computopPayuCeeSingleInitResponseTransfer
            );
        }

        $this->computopEntityManager->updatePaymentComputopOrderItemsStatus(
            $this->getPaymentEntity()->getSpyPaymentComputopOrderItems(),
            $this->getOrderItemPaymentStatusFromResponseHeader($computopPayuCeeSingleInitResponseTransfer->getHeader())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer|null
     */
    protected function getPayuCeeSingleInitResponseFromQuoteTransfer(QuoteTransfer $quoteTransfer): ?ComputopPayuCeeSingleInitResponseTransfer
    {
        $computopPayuCeeSinglePaymentTransfer = $this->getComputopPayuCeeSinglePaymentTransferFromQuote($quoteTransfer);
        if (!($computopPayuCeeSinglePaymentTransfer && $computopPayuCeeSinglePaymentTransfer->getPayuCeeSingleInitResponse())) {
            return null;
        }

        return $computopPayuCeeSinglePaymentTransfer->getPayuCeeSingleInitResponse();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer|null
     */
    protected function getComputopPayuCeeSinglePaymentTransferFromQuote(QuoteTransfer $quoteTransfer): ?ComputopPayuCeeSinglePaymentTransfer
    {
        foreach ($quoteTransfer->getPayments() as $paymentTransfer) {
            if ($paymentTransfer->getComputopPayuCeeSingle()) {
                return $paymentTransfer->getComputopPayuCeeSingle();
            }
        }

        return $quoteTransfer->getPayment() ? $quoteTransfer->getPayment()->getComputopPayuCeeSingle() : null;
    }
}
