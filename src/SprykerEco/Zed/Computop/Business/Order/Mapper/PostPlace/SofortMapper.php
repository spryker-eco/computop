<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Order\Mapper\PostPlace;

use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface;

class SofortMapper implements MapperInterface
{
    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return ComputopConfig::PAYMENT_METHOD_SOFORT;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopSofortPaymentTransfer
     */
    public function getComputopTransfer(PaymentTransfer $paymentTransfer)
    {
        return $paymentTransfer->getComputopSofortOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getComputopResponseTransfer(PaymentTransfer $paymentTransfer)
    {
        return $this->getComputopTransfer($paymentTransfer)->getSofortInitResponse();
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
