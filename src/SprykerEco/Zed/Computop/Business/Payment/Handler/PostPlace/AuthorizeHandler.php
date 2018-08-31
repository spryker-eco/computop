<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace;

use Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class AuthorizeHandler extends AbstractHandler
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $computopApiHeaderPayment
     *
     * @return \Generated\Shared\Transfer\ComputopApiAuthorizeResponseTransfer
     */
    public function handle(OrderTransfer $orderTransfer, ComputopApiHeaderPaymentTransfer $computopApiHeaderPayment)
    {
        $responseTransfer = $this
            ->computopApiFacade
            ->performAuthorizationRequest($orderTransfer, $computopApiHeaderPayment);

        $this->saver->save($responseTransfer, $orderTransfer);

        return $responseTransfer;
    }
}
