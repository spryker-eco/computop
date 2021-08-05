<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Oms\Command\Manager;

use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface;

abstract class AbstractManager implements ManagerInterface
{
    use TransactionTrait;

    /**
     * @var \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Computop\ComputopConfig $config
     */
    public function __construct(ComputopQueryContainerInterface $queryContainer, ComputopConfig $config)
    {
        $this->queryContainer = $queryContainer;
        $this->config = $config;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputop|null
     */
    public function getSavedComputopEntity(int $idSalesOrder): ?SpyPaymentComputop
    {
        return $this
            ->queryContainer
            ->queryPaymentByOrderId($idSalesOrder)
            ->findOne();
    }
}
