<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Order;

use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToOmsInterface;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface;

abstract class AbstractResponseHandler implements OrderResponseHandlerInterface
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
     * @param string $transactionId
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputop
     */
    protected function getPaymentEntity($transactionId)
    {
        if (!isset($this->paymentEntity)) {
            $this->paymentEntity = $this
                ->queryContainer
                ->queryPaymentByTransactionId($transactionId)
                ->findOne();
        }

        return $this->paymentEntity;
    }
}