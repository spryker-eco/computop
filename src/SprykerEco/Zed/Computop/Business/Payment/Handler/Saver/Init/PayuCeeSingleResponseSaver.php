<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init;

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
     * @inheriDoc
     *
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
        $responseTransfer = $quoteTransfer->getPayment()->getComputopPayuCeeSingle()->getPayuCeeSingleInitResponse();
        $this->setPaymentEntity($responseTransfer->getHeader()->getTransId());

        if (!$responseTransfer->getHeader()->getIsSuccess()) {
            return $quoteTransfer;
        }

        $this->getTransactionHandler()->handleTransaction(function () use ($responseTransfer) {
            $this->computopEntityManager->savePaymentEntity($this->getPaymentEntity(), $responseTransfer);
        });

        return $quoteTransfer;
    }
}
