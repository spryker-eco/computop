<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Oms\Command\Manager;

use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface;

abstract class AbstractManager implements ManagerInterface
{
    use DatabaseTransactionHandlerTrait;

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
     * @param integer $idSalesOrder
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface
     */
    public function getSavedComputopEntity($idSalesOrder)
    {
        return $this
            ->queryContainer
            ->queryPaymentByOrderId($idSalesOrder)
            ->findOne();
    }
}
