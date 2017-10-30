<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Mapper\CreditCard;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Business\Api\Mapper\Traits\AuthorizeMapperTrait;

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
        $computopPaymentTransfer->setCapture(ComputopConfig::CAPTURE_MANUAL_TYPE);
        $computopPaymentTransfer->setOrderDesc($this->getOrderDesc($this->computopService, $orderTransfer));

        return $computopPaymentTransfer;
    }
}
