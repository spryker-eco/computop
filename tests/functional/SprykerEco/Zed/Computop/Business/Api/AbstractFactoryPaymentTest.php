<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Computop\Business\Api;

use Functional\SprykerEco\Zed\Computop\AbstractSetUpTest;
use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\ComputopConfig;

abstract class AbstractFactoryPaymentTest extends AbstractSetUpTest
{

    /**
     * @return \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected function createConfig()
    {
        return new ComputopConfig;
    }

    /**
     * @param string $payId
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer($payId)
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setComputopCreditCard($this->createComputopPaymentTransfer($payId));
        $orderTransfer->setTotals(new TotalsTransfer());
        $orderTransfer->setCustomer(new CustomerTransfer());
        return $orderTransfer;
    }

    /**
     * @param string $payId
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function createComputopPaymentTransfer($payId)
    {
        $payment = new ComputopCreditCardPaymentTransfer();
        $payment->setPaymentMethod(ComputopConstants::PAYMENT_METHOD_CREDIT_CARD);
        $payment->setPayId($payId);

        return $payment;
    }

}
