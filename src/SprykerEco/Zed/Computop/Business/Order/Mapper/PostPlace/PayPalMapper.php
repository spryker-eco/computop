<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Order\Mapper\PostPlace;

use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface;

class PayPalMapper implements MapperInterface
{
    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return ComputopConfig::PAYMENT_METHOD_PAY_PAL;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer
     */
    public function getComputopTransfer(PaymentTransfer $paymentTransfer)
    {
        return $paymentTransfer->getComputopPayPalOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalInitResponseTransfer
     */
    public function getComputopResponseTransfer(PaymentTransfer $paymentTransfer)
    {
        return $this->getComputopTransfer($paymentTransfer)->getPayPalInitResponse();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return array
     */
    public function getPaymentDetailsArray(PaymentTransfer $paymentTransfer): array
    {
        return [];
    }
}
