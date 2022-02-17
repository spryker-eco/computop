<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Order\Mapper\PostPlace;

use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface;

class PayPalExpressMapper implements MapperInterface
{
    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return ComputopConfig::PAYMENT_METHOD_PAY_PAL_EXPRESS;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalExpressPaymentTransfer
     */
    public function getComputopTransfer(PaymentTransfer $paymentTransfer)
    {
        return $paymentTransfer->getComputopPayPalExpressOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getComputopResponseTransfer(PaymentTransfer $paymentTransfer)
    {
        return $this->getComputopTransfer($paymentTransfer)->getPayPalExpressInitResponse();
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
