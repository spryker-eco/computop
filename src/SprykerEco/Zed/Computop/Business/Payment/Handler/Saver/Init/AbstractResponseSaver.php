<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver\Init;

use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsFacadeInterface;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface;

abstract class AbstractResponseSaver implements InitResponseSaverInterface
{
    use TransactionTrait;

    /**
     * @var \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface
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
}
