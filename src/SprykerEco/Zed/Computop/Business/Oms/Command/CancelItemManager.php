<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface;

class CancelItemManager implements CancelItemManagerInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @param \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface $queryContainer
     */
    public function __construct(ComputopQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @inheritdoc
     */
    public function changeComputopItemStatus(SpySalesOrderItem $orderItem)
    {
        $this->handleDatabaseTransaction(function () use ($orderItem) {
            $this->changeStatus($orderItem);
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

        $computopOrderItem->setStatus(ComputopConstants::COMPUTOP_OMS_STATUS_CANCELLED);
        $computopOrderItem->save();
    }

}
