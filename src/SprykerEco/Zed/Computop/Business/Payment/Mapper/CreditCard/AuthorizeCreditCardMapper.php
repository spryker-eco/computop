<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\Traits\AuthorizeMapperTrait;

class AuthorizeCreditCardMapper extends AbstractCreditCardMapper
{

    use AuthorizeMapperTrait;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function createPaymentTransfer(OrderTransfer $orderTransfer)
    {
        $computopPaymentTransfer = parent::createPaymentTransfer($orderTransfer);
        $computopPaymentTransfer->setCapture(ComputopConstants::CAPTURE_MANUAL_TYPE);
        $computopPaymentTransfer->setOrderDesc($this->getOrderDesc($this->computopService, $orderTransfer));

        return $computopPaymentTransfer;
    }

}
