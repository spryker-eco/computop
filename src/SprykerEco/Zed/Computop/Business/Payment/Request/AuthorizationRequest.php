<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Request;

use Generated\Shared\Transfer\OrderTransfer;

class AuthorizationRequest extends AbstractPaymentRequest
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function request(OrderTransfer $orderTransfer)
    {
        //TODO: reformat data
        $this->sendRequest($orderTransfer->toArray());
    }

}
