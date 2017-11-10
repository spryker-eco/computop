<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler;

use Generated\Shared\Transfer\ComputopHeaderPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class ReverseHandler extends AbstractHandler
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \Generated\Shared\Transfer\ComputopReverseResponseTransfer
     */
    public function handle(OrderTransfer $orderTransfer, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        /** @var \Generated\Shared\Transfer\ComputopReverseResponseTransfer $responseTransfer */
        $responseTransfer = $this->request->request($orderTransfer, $computopHeaderPayment);
        $this->saver->save($responseTransfer, $orderTransfer);

        return $responseTransfer;
    }
}
