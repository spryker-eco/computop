<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface;

class CancelItemManager implements CancelItemManagerInterface
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
     * @param array $orderItems
     *
     * @return array
     */
    public function changeComputopItemsStatus(array $orderItems)
    {
        $this->handleDatabaseTransaction(function () use ($orderItems) {
            foreach ($orderItems as $orderItem) {
                $this->changeStatus($orderItem);
            }
        });

        return [];
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return void
     */
    protected function changeStatus(SpySalesOrderItem $orderItem)
    {
        $computopOrderItem = $this
            ->queryContainer
            ->queryPaymentItemByOrderItemId($orderItem->getIdSalesOrderItem())
            ->findOne();

        $computopOrderItem->setStatus($this->config->getOmsStatusCancelled());
        $computopOrderItem->save();
    }

}
