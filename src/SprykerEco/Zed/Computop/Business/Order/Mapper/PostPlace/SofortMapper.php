<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Order\Mapper\PostPlace;

use Generated\Shared\Transfer\ComputopSofortPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
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
    public function getComputopTransfer(PaymentTransfer $paymentTransfer): ComputopSofortPaymentTransfer
    {
        return $paymentTransfer->getComputopSofort();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getComputopResponseTransfer(PaymentTransfer $paymentTransfer): TransferInterface
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
