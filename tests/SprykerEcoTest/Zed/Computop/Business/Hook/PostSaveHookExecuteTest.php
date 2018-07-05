<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ComputopInitPaymentTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Zed\Computop\Business\ComputopFacade;
use SprykerEcoTest\Zed\Computop\Order\OrderPaymentTestHelper;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group Computop
 * @group Business
 * @group SaveOrderPaymentTest
 */
class PostSaveHookExecuteTest extends AbstractSetUpTest
{
    const EXPECTED_REDIRECT_URL = 'https://www.computop-paygate.com/sofort.aspx?MerchantID=COMPUTOP%3AMERCHANT_ID&Data=f0957c5d3799a902211fb2e019cef4f459f29d4d908ffc66e6aa0fb839023c62be996fc15b44bcd681ce7bb5ff988c7132af0ed482e94bb18b7c51300761b20d36abc40b4150018f2288bff9340ac0cee2666374f2001af2ca46df7d5a263d3bf479f12682514dec3e232907943934372705bfcf7168e601f3077066797271ad3c48ea551d6158b0b75b385f33ced67648035196b21345712d61dc95be2cd949b5d6de1e41eb5aa2e145d311eaa4f26e8fecd22eaac0d3cc&Len=177';

    /**
     * @return void
     */
    public function testPostSaveHookExecuteWithRedirect()
    {
        $service = new ComputopFacade();
        $service->setFactory($this->orderHelper->createFactory());
        $checkoutResponse = $service->postSaveHookExecute(
            $this->createQuoteTransfer(),
            $this->createCheckoutResponse()
        );

        $this->assertTrue($checkoutResponse->getIsExternalRedirect());
        $this->assertSame(self::EXPECTED_REDIRECT_URL, $checkoutResponse->getRedirectUrl());
    }

    /**
     * @return void
     */
    public function testPostSaveHookExecuteWithoutRedirect()
    {
        $service = new ComputopFacade();
        $service->setFactory($this->orderHelper->createFactory());
        $quoteTransfer = $this->createQuoteTransfer();
        $quoteTransfer->getPayment()->setPaymentSelection(ComputopSharedConfig::PAYMENT_METHOD_PAY_NOW);

        $checkoutResponse = $service->postSaveHookExecute(
            $quoteTransfer,
            $this->createCheckoutResponse()
        );

        $this->assertInstanceOf(ComputopInitPaymentTransfer::class, $checkoutResponse->getComputopInitPayment());
        $this->assertSame(
            OrderPaymentTestHelper::DATA_VALUE,
            $checkoutResponse->getComputopInitPayment()->getData()
        );
        $this->assertSame(
            OrderPaymentTestHelper::LEN_VALUE,
            $checkoutResponse->getComputopInitPayment()->getLen()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function createCheckoutResponse()
    {
        $checkoutResponse = new CheckoutResponseTransfer();
        $checkoutResponse->setSaveOrder((new SaveOrderTransfer)->setOrderReference('DE--1'));

        return $checkoutResponse;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        $quoteTransfer = new QuoteTransfer();
        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setComputopSofort($this->orderHelper->createComputopSofortPaymentTransfer());
        $paymentTransfer->setComputopPayNow($this->orderHelper->createComputopPayNowPaymentTransfer());
        $paymentTransfer->setPaymentMethod(ComputopSharedConfig::PAYMENT_METHOD_SOFORT);
        $paymentTransfer->setPaymentProvider(ComputopSharedConfig::PROVIDER_NAME);
        $paymentTransfer->setPaymentSelection(ComputopSharedConfig::PAYMENT_METHOD_SOFORT);
        $quoteTransfer->setPayment($paymentTransfer);
        $totals = new TotalsTransfer();
        $totals->setGrandTotal(10);
        $quoteTransfer->setTotals($totals);
        $quoteTransfer->setCustomer(new CustomerTransfer());
        return $quoteTransfer;
    }
}
