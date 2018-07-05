<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace;

use Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface HandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $computopApiHeaderPayment
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function handle(
        OrderTransfer $orderTransfer,
        ComputopApiHeaderPaymentTransfer $computopApiHeaderPayment
    );
}
