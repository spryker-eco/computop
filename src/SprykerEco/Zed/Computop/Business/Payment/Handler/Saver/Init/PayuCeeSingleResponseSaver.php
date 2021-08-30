<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init;

use Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Computop\ComputopConfig as SharedComputopConfig;
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

        $responseTransfer = $quoteTransfer->getPayment()->getComputopPayuCeeSingle()->getPayuCeeSingleInitResponse();
        if ($responseTransfer->getHeader()->getIsSuccess()) {
            $this->handleSaveTransaction($responseTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function handleSaveTransaction(ComputopPayuCeeSingleInitResponseTransfer $responseTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($responseTransfer) {
            $this->executeSavePaymentResponseTransaction($responseTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function executeSavePaymentResponseTransaction(ComputopPayuCeeSingleInitResponseTransfer $responseTransfer): void
    {
        $paymentStatus = $this->getPaymentStatus($responseTransfer);
        $this->computopEntityManager->savePaymentResponse($responseTransfer, $paymentStatus);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $responseTransfer
     *
     * @return string
     */
    protected function getPaymentStatus(ComputopPayuCeeSingleInitResponseTransfer $responseTransfer): string
    {
        $responseStatus = $this->getResponseStatus($responseTransfer);
        if ($responseStatus === null) {
            return $this->config->getOmsStatusNew();
        }

        if ($responseStatus === SharedComputopConfig::AUTHORIZE_REQUEST_STATUS) {
            return $this->config->getAuthorizeRequestOmsStatus();
        }

        $responseDescription = $responseTransfer->getHeader()->getDescription();
        if ($responseStatus === SharedComputopConfig::SUCCESS_OK && $responseDescription === SharedComputopConfig::SUCCESS_STATUS) {
            return $this->config->getOmsStatusAuthorized();
        }

        return $this->config->getOmsStatusNew();
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer $responseTransfer
     *
     * @return string|null
     */
    protected function getResponseStatus(ComputopPayuCeeSingleInitResponseTransfer $responseTransfer): ?string
    {
        if ($responseTransfer && $responseTransfer->getHeader()) {
            return $responseTransfer->getHeader()->getStatus();
        }

        return null;
    }
}
