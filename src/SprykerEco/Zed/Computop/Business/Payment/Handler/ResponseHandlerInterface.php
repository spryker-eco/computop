<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler;

use Generated\Shared\Transfer\ComputopCreditCardAuthorizeResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface ResponseHandlerInterface
{

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardAuthorizeResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function handle(
        ComputopCreditCardAuthorizeResponseTransfer $responseTransfer,
        OrderTransfer $orderTransfer
    );

}
