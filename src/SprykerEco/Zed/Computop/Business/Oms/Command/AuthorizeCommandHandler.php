<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Oms\Command;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

class AuthorizeCommandHandler extends AbstractCommandHandler
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function handle(array $orderItems, OrderTransfer $orderTransfer)
    {
        $computopHeaderPayment = $this->createComputopHeaderPayment($orderTransfer);

        return $this->handler->handle($orderTransfer, $computopHeaderPayment);
    }
}
