<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Api;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ComputopHeaderPaymentTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerEco\Shared\Computop\ComputopConfig as SharedComputopConfig;
use SprykerEco\Zed\Computop\ComputopConfig;

class ApiPaymentTestHelper extends Test
{
    /**
     * @return \SprykerEco\Zed\Computop\ComputopConfig
     */
    public function createConfig()
    {
        return new ComputopConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setTotals(new TotalsTransfer());
        $orderTransfer->setCustomer(new CustomerTransfer());
        $orderTransfer->addPayment($this->createPaymentTransfer());
        return $orderTransfer;
    }

    /**
     * @param string $payId
     * @param string $transId
     *
     * @return \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer
     */
    public function createComputopHeaderPaymentTransfer($payId, $transId)
    {
        $headerPayment = new ComputopHeaderPaymentTransfer();
        $headerPayment->setAmount(100);
        $headerPayment->setPayId($payId);
        $headerPayment->setTransId($transId);

        return $headerPayment;
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function createPaymentTransfer()
    {
        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentProvider(SharedComputopConfig::PROVIDER_NAME)
            ->setPaymentMethod(SharedComputopConfig::PAYMENT_METHOD_CREDIT_CARD)
            ->setPaymentSelection(SharedComputopConfig::PAYMENT_METHOD_CREDIT_CARD);

        return $paymentTransfer;
    }
}
