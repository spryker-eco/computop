<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Request;

use Generated\Shared\Transfer\OrderTransfer;

class CaptureRequest extends AbstractPaymentRequest implements CreditCartRequestInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return mixed
     */
    public function request(OrderTransfer $orderTransfer)
    {
        $requestData = $this
            ->getMethodMapper($this->paymentMethod)
            ->buildRequest($orderTransfer);

        return $this->sendRequest($requestData);
    }

}