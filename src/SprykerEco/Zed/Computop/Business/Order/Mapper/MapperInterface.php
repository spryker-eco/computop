<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Order\Mapper;

use Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer;
use Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;

interface MapperInterface
{
    /**
     * @return string
     */
    public function getMethodName(): string;

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer|null
     */
    public function getComputopTransfer(PaymentTransfer $paymentTransfer): ?ComputopPayuCeeSinglePaymentTransfer;

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer|null
     */
    public function getComputopResponseTransfer(PaymentTransfer $paymentTransfer): ?ComputopPayuCeeSingleInitResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return array
     */
    public function getPaymentDetailsArray(PaymentTransfer $paymentTransfer): array;
}
