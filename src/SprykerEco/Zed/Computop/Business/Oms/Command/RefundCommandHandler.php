<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Oms\Command;

use Generated\Shared\Transfer\OrderTransfer;

/**
 * @property \SprykerEco\Zed\Computop\Business\Oms\Command\Manager\RefundManager $manager
 */
class RefundCommandHandler extends AbstractCommandHandler
{
    /**
     * @var \SprykerEco\Zed\Computop\Business\Oms\Command\Manager\ManagerInterface
     */
    protected $manager;

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function handle(array $orderItems, OrderTransfer $orderTransfer)
    {
        $computopHeaderPayment = $this->createComputopHeaderPayment($orderTransfer);

        return $this->handler->handle($orderTransfer, $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getAmount(OrderTransfer $orderTransfer): int
    {
        return $this->manager->getAmount($orderTransfer);
    }
}
