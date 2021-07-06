<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Yves\Computop\Mapper\Init\PrePlace;

use Codeception\TestCase\Test;
use SprykerEco\Yves\Computop\Mapper\Init\PrePlace\PayPalExpressToQuoteMapper;
use SprykerEcoTest\Yves\Computop\Mapper\PayPalExpressToQuoteMapperTestConstants;

class PayPalExpressToQuoteMapperTest extends Test
{
    /**
     * @var \SprykerEcoTest\Yves\Computop\ComputopYvesTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMapAddressTransfer(): void
    {
        //Arrange
        $mapper = $this->createPayPalExpressToQuoteMapper();

        //Act
        $quoteTransfer = $mapper->mapAddressFromComputopPayPalExpressInitResponseToQuote(
            $this->tester->createComputopPayPalExpressInitResponseTransfer(),
            $this->tester->createQuoteTransfer()
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
            $this->assertSame(
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

    /**
     * @return void
     */
    public function testMapBillingTransfer(): void
    {
        //Arrange
        $mapper = $this->createPayPalExpressToQuoteMapper();

        //Act
        $quoteTransfer = $mapper->mapBillingAddressFromComputopPayPalExpressInitResponseToQuote(
            $this->tester->createComputopPayPalExpressInitResponseTransfer(),
            $this->tester->createQuoteTransfer()
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

    /**
     * @return void
     */
    public function testMapCustomerTransfer(): void
    {
        //Arrange
        $mapper = $this->createPayPalExpressToQuoteMapper();

        //Act
        $quoteTransfer = $mapper->mapCustomerFromComputopPayPalExpressInitResponseToQuote(
            $this->tester->createComputopPayPalExpressInitResponseTransfer(),
            $this->tester->createQuoteTransfer()
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
     * @return \SprykerEco\Yves\Computop\Mapper\Init\PrePlace\PayPalExpressToQuoteMapper
     */
    protected function createPayPalExpressToQuoteMapper(): PayPalExpressToQuoteMapper
    {
        return new PayPalExpressToQuoteMapper();
    }
}
