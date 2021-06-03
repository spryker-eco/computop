<?php

namespace SprykerEcoTest\Yves\Computop\Mapper;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer;
use Generated\Shared\Transfer\ComputopPayPalExpressPaymentTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\TotalsTransfer;

class PayPalExpressToQuoteMapperTestHelper extends Test
{
    /**
     * @return QuoteTransfer
     */
    public function createQuoteTransfer(): QuoteTransfer
    {
        $quoteTransfer = new QuoteTransfer();

        $paymentTransfer = new PaymentTransfer();
        $computopPayPalExpressPaymentTransfer = new ComputopPayPalExpressPaymentTransfer();
        $paymentTransfer->setComputopPayPalExpress($computopPayPalExpressPaymentTransfer);
        $quoteTransfer->setPayment($paymentTransfer);

        $totalsTransfer = new TotalsTransfer();
        $quoteTransfer->setTotals($totalsTransfer);

        $itemsArray = new \ArrayObject();
        $itemsArray->append($this->createItemTransfer());
        $quoteTransfer->setItems($itemsArray);

        $expensesArray = new \ArrayObject();
        $expensesArray->append($this->createExpenseTransfer());
        $quoteTransfer->setExpenses($expensesArray);

        return $quoteTransfer;
    }

    /**
     * @return ComputopPayPalExpressInitResponseTransfer
     */
    public function createComputopPayPalExpressInitResponseTransfer(): ComputopPayPalExpressInitResponseTransfer
    {
        $computopPayPalExpressInitResponseTransfer = new ComputopPayPalExpressInitResponseTransfer();
        $computopPayPalExpressInitResponseTransfer
            ->setMerchantId(PayPalExpressToQuoteMapperTestConstants::MERCHANT_ID_SHORT_VALUE)
            ->setPayId(PayPalExpressToQuoteMapperTestConstants::PAY_ID_VALUE)
            ->setFirstName(PayPalExpressToQuoteMapperTestConstants::FIRST_NAME_VALUE)
            ->setLastName(PayPalExpressToQuoteMapperTestConstants::LAST_NAME_VALUE)
            ->setEmail(PayPalExpressToQuoteMapperTestConstants::EMAIL_VALUE)
            ->setAddressStreet(PayPalExpressToQuoteMapperTestConstants::ADDRESS_STREET_VALUE)
            ->setAddressCountryCode(PayPalExpressToQuoteMapperTestConstants::ADDRESS_COUNTRY_CODE_VALUE)
            ->setAddressCity(PayPalExpressToQuoteMapperTestConstants::ADDRESS_CITY_VALUE)
            ->setAddressZip(PayPalExpressToQuoteMapperTestConstants::ADDR_ZIP_VALUE)
            ->setBillingName(PayPalExpressToQuoteMapperTestConstants::BILLING_NAME_VALUE)
            ->setBillingAddressStreet(PayPalExpressToQuoteMapperTestConstants::BILLING_ADDRESS_STREET_VALUE)
            ->setBillingAddressCountryCode(PayPalExpressToQuoteMapperTestConstants::BILLING_ADDRESS_COUNTRY_CODE_VALUE)
            ->setBillingAddressCity(PayPalExpressToQuoteMapperTestConstants::BILLING_ADDRESS_CITY_VALUE)
            ->setBillingAddressZip(PayPalExpressToQuoteMapperTestConstants::BILLING_ADDRESS_ZIP_VALUE);

        return $computopPayPalExpressInitResponseTransfer;
    }

    /**
     * @return ItemTransfer
     */
    protected function createItemTransfer(): ItemTransfer
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setShipment(new ShipmentTransfer());

        return $itemTransfer;
    }

    /**
     * @return ExpenseTransfer
     */
    protected function createExpenseTransfer(): ExpenseTransfer
    {
        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setShipment(new ShipmentTransfer());

        return $expenseTransfer;
    }
}
