<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Computop\Business;

use Functional\SprykerEco\Zed\Computop\AbstractSetUpTest;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ComputopCreditCardOrderResponseTransfer;
use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

abstract class AbstractFactoryOrderPaymentTest extends AbstractSetUpTest
{

    const PAY_ID_VALUE = 'e1ebdc5bd6054263a52ef33ecae1ccda';
    const TRANS_ID_VALUE = '585e330ea1fe696ffb0bad71db503504';
    const CLIENT_IP_VALUE = '0.0.0.0';

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function createCheckoutResponse()
    {
        $checkoutResponceTransfer = new CheckoutResponseTransfer();
        $checkoutResponceTransfer->setSaveOrder($this->createSaveOrderTransfer());

        return $checkoutResponceTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function createSaveOrderTransfer()
    {
        $saveOrderTransfer = new SaveOrderTransfer();
        $saveOrderTransfer->setIdSalesOrder($this->orderEntity->getIdSalesOrder());
        $saveOrderTransfer->setOrderReference($this->orderEntity->getOrderReference());

        return $saveOrderTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
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
    protected function createComputopPaymentTransfer()
    {
        $computopPayment = new ComputopCreditCardPaymentTransfer();
        $computopPayment->setPaymentMethod(ComputopConstants::PAYMENT_METHOD_CREDIT_CARD);
        $computopPayment->setPayId(self::PAY_ID_VALUE);
        $computopPayment->setClientIp(self::CLIENT_IP_VALUE);
        $computopPayment->setTransId(self::TRANS_ID_VALUE);
        $computopPayment->setCreditCardOrderResponse($this->createComputopOrderResponseTransfer());

        return $computopPayment;
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopCreditCardOrderResponseTransfer
     */
    protected function createComputopOrderResponseTransfer()
    {
        $computopResponse = new ComputopCreditCardOrderResponseTransfer();
        $computopResponse->setHeader($this->createComputopResponseHeaderTransfer());

        return $computopResponse;
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    protected function createComputopResponseHeaderTransfer()
    {
        $computopHeaderResponse = new ComputopResponseHeaderTransfer();
        $computopHeaderResponse->setPayId(self::PAY_ID_VALUE);

        return $computopHeaderResponse;
    }

}
