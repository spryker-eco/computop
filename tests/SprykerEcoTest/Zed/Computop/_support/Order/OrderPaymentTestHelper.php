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
    const CURRENCY_VALUE = 'USD';
    const DATA_VALUE = 'f0957c5d3799a902211fb2e019cef4f459f29d4d908ffc66e6aa0fb839023c62be996fc15b44bcd681ce7bb5ff988c7132af0ed482e94bb18b7c51300761b20d36abc40b4150018f2288bff9340ac0cee2666374f2001af2ca46df7d5a263d3bf479f12682514dec3e232907943934372705bfcf7168e601f3077066797271ad3c48ea551d6158b0b75b385f33ced67648035196b21345712d61dc95be2cd949b5d6de1e41eb5aa2e145d311eaa4f26e8fecd22eaac0d3cc';
    const LEN_VALUE = 177;

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
