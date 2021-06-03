<?php

namespace SprykerEcoTest\Yves\Computop\Mapper\Init\PrePlace;

use Codeception\TestCase\Test;
use SprykerEco\Yves\Computop\Mapper\Init\PrePlace\PayPalExpressToQuoteMapper;
use SprykerEcoTest\Yves\Computop\Mapper\PayPalExpressToQuoteMapperTestConstants;
use SprykerEcoTest\Yves\Computop\Mapper\PayPalExpressToQuoteMapperTestHelper;

class PayPalExpressToQuoteMapperTest extends Test
{
    /**
     * @var PayPalExpressToQuoteMapperTestHelper
     */
    protected $helper;

    protected function _inject(PayPalExpressToQuoteMapperTestHelper $helper): void
    {
        $this->helper = $helper;
    }

    public function testMapAddressTransfer(): void
    {
        //Arrange
        $mapper = $this->createPayPalExpressToQuoteMapper();

        //Act
        $quoteTransfer = $mapper->mapAddressTransfer(
            $this->helper->createQuoteTransfer(),
            $this->helper->createComputopPayPalExpressInitResponseTransfer()
        );

        //Assert
        $this->assertNotNull($quoteTransfer->getShippingAddress());
        $this->assertNotNull($quoteTransfer->getShippingAddress()->getCountry());
        $this->assertEquals(
            PayPalExpressToQuoteMapperTestConstants::ADDRESS_COUNTRY_CODE_VALUE,
            $quoteTransfer->getShippingAddress()->getCountry()->getIso2Code()
        );
        $this->assertEquals(
            PayPalExpressToQuoteMapperTestConstants::FIRST_NAME_VALUE,
            $quoteTransfer->getShippingAddress()->getFirstName()
        );

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertEquals(
                PayPalExpressToQuoteMapperTestConstants::ADDRESS_CITY_VALUE,
                $itemTransfer->getShipment()->getShippingAddress()->getCity()
            );
        }

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $this->assertEquals(
                PayPalExpressToQuoteMapperTestConstants::ADDRESS_STREET_VALUE,
                $expenseTransfer->getShipment()->getShippingAddress()->getAddress1()
            );
        }
    }

    public function testMapBillingTransfer(): void
    {
        //Arrange
        $mapper = $this->createPayPalExpressToQuoteMapper();

        //Act
        $quoteTransfer = $mapper->mapBillingTransfer(
            $this->helper->createQuoteTransfer(),
            $this->helper->createComputopPayPalExpressInitResponseTransfer()
        );

        //Assert
        $this->assertNotNull($quoteTransfer->getBillingAddress());
        $this->assertNotNull($quoteTransfer->getBillingAddress()->getCountry());
        $this->assertEquals(
            PayPalExpressToQuoteMapperTestConstants::BILLING_ADDRESS_COUNTRY_CODE_VALUE,
            $quoteTransfer->getBillingAddress()->getCountry()->getIso2Code()
        );
        $this->assertEquals(
            PayPalExpressToQuoteMapperTestConstants::BILLING_NAME_VALUE,
            $quoteTransfer->getBillingAddress()->getFirstName()
        );
    }

    public function testMapCustomerTransfer(): void
    {
        //Arrange
        $mapper = $this->createPayPalExpressToQuoteMapper();

        //Act
        $quoteTransfer = $mapper->mapCustomerTransfer(
            $this->helper->createQuoteTransfer(),
            $this->helper->createComputopPayPalExpressInitResponseTransfer()
        );

        //Assert
        $this->assertNotNull($quoteTransfer->getCustomer());
        $this->assertEquals(
            PayPalExpressToQuoteMapperTestConstants::FIRST_NAME_VALUE,
            $quoteTransfer->getCustomer()->getFirstName()
        );
        $this->assertEquals(
            PayPalExpressToQuoteMapperTestConstants::LAST_NAME_VALUE,
            $quoteTransfer->getCustomer()->getLastName()
        );
        $this->assertEquals(
            PayPalExpressToQuoteMapperTestConstants::EMAIL_VALUE,
            $quoteTransfer->getCustomer()->getEmail()
        );

        $this->assertTrue($quoteTransfer->getCustomer()->getIsGuest());
        $this->assertTrue($quoteTransfer->getAcceptTermsAndConditions());
    }

    /**
     * @return PayPalExpressToQuoteMapper
     */
    protected function createPayPalExpressToQuoteMapper(): PayPalExpressToQuoteMapper
    {
        return new PayPalExpressToQuoteMapper();
    }
}
