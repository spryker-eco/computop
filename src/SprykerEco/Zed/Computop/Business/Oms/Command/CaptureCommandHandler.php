<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Oms\Command;

use Generated\Shared\Transfer\OrderTransfer;

/**
 * @property \SprykerEco\Zed\Computop\Business\Oms\Command\Manager\CaptureManager $manager
 */
class CaptureCommandHandler extends AbstractCommandHandler
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array|\Spryker\Shared\Kernel\Transfer\TransferInterface
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
    protected function getAmount(OrderTransfer $orderTransfer)
    {
        if ($this->isFirstCapture($orderTransfer)) {
            return $orderTransfer->getTotals()->getGrandTotal();
        }

        return $orderTransfer->getTotals()->getSubtotal() - $orderTransfer->getTotals()->getDiscountTotal();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function isFirstCapture(OrderTransfer $orderTransfer)
    {
        $itemsBeforeCaptureStateCount = count($this->manager->getItemsBeforeCaptureState($orderTransfer));
        $allItemsCount = count($this->manager->getAllItems($orderTransfer));

        return $itemsBeforeCaptureStateCount === $allItemsCount;
    }
}
