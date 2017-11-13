<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Order;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ComputopCreditCardInitResponseTransfer;
use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopSofortPaymentTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerEco\Service\Computop\ComputopService;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Zed\Computop\Business\ComputopBusinessFactory;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainer;

class OrderPaymentTestHelper extends Test
{
    const CURRENCY_VALUE = 'USD';

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
                'getComputopService',
            ]
        );

        $stub = $builder->getMock();

        $stub->method('getConfig')
            ->willReturn($this->createConfig());

        $stub->method('getQueryContainer')
            ->willReturn(new ComputopQueryContainer());

        $stub->method('getComputopService')
            ->willReturn(new ComputopService());

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
        $paymentTransfer->setPaymentMethod(ComputopSharedConfig::PAYMENT_METHOD_CREDIT_CARD);
        $paymentTransfer->setPaymentProvider(ComputopSharedConfig::PROVIDER_NAME);
        $paymentTransfer->setPaymentSelection(ComputopSharedConfig::PAYMENT_METHOD_CREDIT_CARD);
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
        $computopPayment->setPaymentMethod(ComputopSharedConfig::PAYMENT_METHOD_CREDIT_CARD);
        $computopPayment->setPayId(OrderPaymentTestConstants::PAY_ID_VALUE);
        $computopPayment->setClientIp(OrderPaymentTestConstants::CLIENT_IP_VALUE);
        $computopPayment->setTransId(OrderPaymentTestConstants::TRANS_ID_VALUE);
        $computopPayment->setCreditCardInitResponse($this->createComputopOrderResponseTransfer());

        return $computopPayment;
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopSofortPaymentTransfer
     */
    public function createComputopSofortPaymentTransfer()
    {
        $computopPayment = new ComputopSofortPaymentTransfer();
        $computopPayment->setPayId(OrderPaymentTestConstants::PAY_ID_VALUE);
        $computopPayment->setClientIp(OrderPaymentTestConstants::CLIENT_IP_VALUE);
        $computopPayment->setTransId(OrderPaymentTestConstants::TRANS_ID_VALUE);
        $computopPayment->setCurrency(self::CURRENCY_VALUE);

        return $computopPayment;
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopCreditCardInitResponseTransfer
     */
    public function createComputopOrderResponseTransfer()
    {
        $computopResponse = new ComputopCreditCardInitResponseTransfer();
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
