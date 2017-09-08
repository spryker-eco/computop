<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Order\Mapper;

use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

class CreditCardMapper implements MapperInterface
{

    /**
     * @return string
     */
    public function getMethodName()
    {
        return ComputopConstants::PAYMENT_METHOD_CREDIT_CARD;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getComputopTransfer(PaymentTransfer $paymentTransfer)
    {
        return $paymentTransfer->getComputopCreditCard();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getComputopResponseTransfer(PaymentTransfer $paymentTransfer)
    {
        return $this->getComputopTransfer($paymentTransfer)->getCreditCardOrderResponse();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return array
     */
    public function getPaymentDetailForOrderArray(PaymentTransfer $paymentTransfer)
    {
        return $this->getComputopResponseTransfer($paymentTransfer)->toArray();
    }

}
