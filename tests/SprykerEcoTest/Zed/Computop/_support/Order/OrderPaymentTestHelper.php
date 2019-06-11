<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Order;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopCreditCardInitResponseTransfer;
use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\ComputopPayNowPaymentTransfer;
use Generated\Shared\Transfer\ComputopSofortPaymentTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerEco\Service\ComputopApi\ComputopApiService;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Zed\Computop\Business\ComputopBusinessFactory;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainer;

class OrderPaymentTestHelper extends Test
{
    public const CURRENCY_VALUE = 'USD';
    public const DATA_VALUE = 'f0957c5d3799a902211fb2e019cef4f459f29d4d908ffc66e6aa0fb839023c62be996fc15b44bcd681ce7bb5ff988c7157707228fb13b961fd676abedef6aeed48817998d16a635e11943e70942b91751a5793ce85a43e6a38220eae6bdbb594f3dcfb398e2f8f9a1d9aa922a05821950ff5dbae45eaf9a5818fe75ad0a8e246df61165cf5fe6727b922edf08e6d7b40008bd199d12331e44eea8f8191955d6e832653b596efee16';
    public const LEN_VALUE = 165;

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
                'getComputopApiService',
            ]
        );

        $stub = $builder->getMock();

        $stub->method('getConfig')
            ->willReturn($this->createConfig());

        $stub->method('getQueryContainer')
            ->willReturn(new ComputopQueryContainer());

        $stub->method('getComputopApiService')
            ->willReturn(new ComputopApiService());

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
     * @return \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer
     */
    public function createComputopPayNowPaymentTransfer()
    {
        $computopPayment = new ComputopPayNowPaymentTransfer();
        $computopPayment->setPayId(OrderPaymentTestConstants::PAY_ID_VALUE);
        $computopPayment->setClientIp(OrderPaymentTestConstants::CLIENT_IP_VALUE);
        $computopPayment->setTransId(OrderPaymentTestConstants::TRANS_ID_VALUE);
        $computopPayment->setCurrency(self::CURRENCY_VALUE);
        $computopPayment->setData(self::DATA_VALUE);
        $computopPayment->setLen(self::LEN_VALUE);

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
     * @return \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer
     */
    public function createComputopResponseHeaderTransfer()
    {
        $computopHeaderResponse = new ComputopApiResponseHeaderTransfer();
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
