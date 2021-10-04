<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init;

use Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer;
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
        if (
            !$quoteTransfer->getPayment() ||
            !$quoteTransfer->getPayment()->getComputopPayuCeeSingle() ||
            !$quoteTransfer->getPayment()->getComputopPayuCeeSingle()->getPayuCeeSingleInitResponse()
        ) {
            return $quoteTransfer;
        }

        $computopPayuCeeSingleInitResponse = $quoteTransfer->getPayment()->getComputopPayuCeeSingle()->getPayuCeeSingleInitResponse();
        if ($computopPayuCeeSingleInitResponse->getHeader()->getIsSuccess()) {
            $orderItemStatus = $this->getOrderItemPaymentStatusFromInitResponse($computopPayuCeeSingleInitResponse);
            $this->handleSaveTransaction($computopPayuCeeSingleInitResponse, $orderItemStatus);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer
     * @param string $orderItemsStatus
     *
     * @return void
     */
    protected function handleSaveTransaction(
        ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer,
        string $orderItemsStatus
    ): void {
        $this->getTransactionHandler()->handleTransaction(function () use ($computopPayuCeeSingleInitResponseTransfer, $orderItemsStatus) {
            $this->executeSavePaymentResponseTransaction($computopPayuCeeSingleInitResponseTransfer, $orderItemsStatus);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer
     * @param string $orderItemsStatus
     *
     * @return void
     */
    protected function executeSavePaymentResponseTransaction(
        ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer,
        string $orderItemsStatus
    ): void {
        $this->computopEntityManager->saveComputopPayuCeeSingleInitResponse(
            $computopPayuCeeSingleInitResponseTransfer,
            $orderItemsStatus
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer
     *
     * @return string
     */
    protected function getOrderItemPaymentStatusFromInitResponse(ComputopPayuCeeSingleInitResponseTransfer $computopPayuCeeSingleInitResponseTransfer): string
    {
        $computopApiResponseHeaderTransfer = $computopPayuCeeSingleInitResponseTransfer->getHeader();
        if ($computopApiResponseHeaderTransfer === null) {
            return $this->config->getOmsStatusNew();
        }

        return $this->getOrderItemPaymentStatusFromComputopApiResponseHeaderTransfer($computopApiResponseHeaderTransfer);
    }
}
