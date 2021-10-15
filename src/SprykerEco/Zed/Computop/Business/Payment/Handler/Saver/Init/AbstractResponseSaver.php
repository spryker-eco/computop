<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Shared\Computop\ComputopConfig as SharedComputopConfig;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsFacadeInterface;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface;

abstract class AbstractResponseSaver implements InitResponseSaverInterface
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
    ) {
        $this->queryContainer = $queryContainer;
        $this->omsFacade = $omsFacade;
        $this->config = $config;
    }

    /**
     * @param string $transactionId
     *
     * @return void
     */
    protected function setPaymentEntity($transactionId)
    {
        $this->paymentEntity = $this->queryContainer->queryPaymentByTransactionId($transactionId)->findOne();
    }

    /**
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputop
     */
    protected function getPaymentEntity()
    {
        return $this->paymentEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer
     *
     * @return string
     */
    protected function getOrderItemPaymentStatusFromResponseHeader(
        ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer
    ): string {
        if ($computopApiResponseHeaderTransfer->getStatus() === null) {
            return $this->config->getOmsStatusNew();
        }

        if ($computopApiResponseHeaderTransfer->getStatus() === SharedComputopConfig::AUTHORIZE_REQUEST_STATUS) {
            return $this->config->getAuthorizeRequestOmsStatus();
        }

        if ($computopApiResponseHeaderTransfer->getStatus() === SharedComputopConfig::SUCCESS_OK) {
            return $this->config->getOmsStatusAuthorized();
        }

        return $this->config->getOmsStatusNew();
    }
}
