<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Oms\Command;

use Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Computop\Business\Oms\Command\Manager\ManagerInterface;
use SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\HandlerInterface;

abstract class AbstractCommandHandler implements CommandHandlerInterface
{
    /**
     * @var \SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\HandlerInterface
     */
    protected $handler;

    /**
     * @var \SprykerEco\Zed\Computop\Business\Oms\Command\Manager\ManagerInterface
     */
    protected $manager;

    /**
     * @param \SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace\HandlerInterface $handler
     * @param \SprykerEco\Zed\Computop\Business\Oms\Command\Manager\ManagerInterface $manager
     */
    public function __construct(
        HandlerInterface $handler,
        ManagerInterface $manager
    ) {
        $this->handler = $handler;
        $this->manager = $manager;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer
     */
    protected function createComputopHeaderPayment(OrderTransfer $orderTransfer): ComputopApiHeaderPaymentTransfer
    {
        $headerPayment = new ComputopApiHeaderPaymentTransfer();
        $savedComputopEntity = $this->manager->getSavedComputopEntity($orderTransfer->getIdSalesOrderOrFail());
        $headerPayment->fromArray($savedComputopEntity->toArray(), true);
        $headerPayment->setAmount($this->getAmount($orderTransfer));

        return $headerPayment;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getAmount(OrderTransfer $orderTransfer): int
    {
        return (int)$orderTransfer->getTotalsOrFail()->getGrandTotal();
    }
}
