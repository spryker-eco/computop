<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Computop\Helper\Functional\Order;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ComputopCreditCardOrderResponseTransfer;
use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\Business\ComputopBusinessFactory;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainer;

class OrderPaymentTestHelper extends Test
{

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject | ComputopBusinessFactory
     */
    public function createFactory()
    {
        $builder = $this->getMockBuilder(ComputopBusinessFactory::class);
        $builder->setMethods(
            [
                'getConfig',
                'getQueryContainer',
            ]
        );

        $stub = $builder->getMock();

        $stub->method('getConfig')
            ->willReturn($this->createConfig());

        $stub->method('getQueryContainer')
            ->willReturn(new ComputopQueryContainer());

        return $stub;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer()
    {
        $quoteTransfer = new QuoteTransfer();
        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setComputopCreditCard($this->createComputopPaymentTransfer());
        $paymentTransfer->setPaymentMethod(ComputopConstants::PAYMENT_METHOD_CREDIT_CARD);
        $paymentTransfer->setPaymentProvider(ComputopConstants::PROVIDER_NAME);
        $quoteTransfer->setPayment($paymentTransfer);
        $quoteTransfer->setTotals(new TotalsTransfer());
        $quoteTransfer->setCustomer(new CustomerTransfer());
        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    public function createComputopPaymentTransfer()
    {
        $computopPayment = new ComputopCreditCardPaymentTransfer();
        $computopPayment->setPaymentMethod(ComputopConstants::PAYMENT_METHOD_CREDIT_CARD);
        $computopPayment->setPayId(OrderPaymentTestConstants::PAY_ID_VALUE);
        $computopPayment->setClientIp(OrderPaymentTestConstants::CLIENT_IP_VALUE);
        $computopPayment->setTransId(OrderPaymentTestConstants::TRANS_ID_VALUE);
        $computopPayment->setCreditCardOrderResponse($this->createComputopOrderResponseTransfer());

        return $computopPayment;
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopCreditCardOrderResponseTransfer
     */
    public function createComputopOrderResponseTransfer()
    {
        $computopResponse = new ComputopCreditCardOrderResponseTransfer();
        $computopResponse->setHeader($this->createComputopResponseHeaderTransfer());

        return $computopResponse;
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function createComputopResponseHeaderTransfer()
    {
        $computopHeaderResponse = new ComputopResponseHeaderTransfer();
        $computopHeaderResponse->setPayId(OrderPaymentTestConstants::PAY_ID_VALUE);

        return $computopHeaderResponse;
    }

    /**
     * @return \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected function createConfig()
    {
        return new ComputopConfig();
    }

}
