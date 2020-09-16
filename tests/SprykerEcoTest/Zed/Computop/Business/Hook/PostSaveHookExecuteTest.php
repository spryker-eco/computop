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
    public const EXPECTED_REDIRECT_URL = 'https://www.computop-paygate.com/sofort.aspx?MerchantID=COMPUTOP%3AMERCHANT_ID&Data=f0957c5d3799a902211fb2e019cef4f459f29d4d908ffc66e6aa0fb839023c62be996fc15b44bcd681ce7bb5ff988c7132af0ed482e94bb18b7c51300761b20d36abc40b4150018fd598db604904909fc403bf51d0af4415ab799ff871797feb65eb44321cc877f93d318915903ae4fc9718a694ca26de347d7354253bd8ff41b0e9dce681908fb3846bb1b1faff62686c5d56c3dda2236eee1eddf9a4173b11f7bfa6218d66239d5eaa177f6d82e59cf9fc0025fe134910b00dbaacee244698008bd199d12331e44eea8f8191955d6e7cbf35746b2684f898d9a60cf52b0fcfcfee78039653b6e1ace5a8a409d4edb1&Len=235';

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
        $checkoutResponse->setSaveOrder((new SaveOrderTransfer())->setOrderReference('DE--1'));

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
