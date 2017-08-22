<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface ResponseHandlerInterface
{

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function handle(
        TransferInterface $responseTransfer,
        OrderTransfer $orderTransfer
    );

}
