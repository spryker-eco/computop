<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PrePlace;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class PayPalExpressToQuoteMapper implements PayPalExpressToQuoteMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapAddressFromComputopPayPalExpressInitResponseToQuote(
        ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer
    {
        $countryTransfer = new CountryTransfer();
        $countryTransfer->setIso2Code($computopPayPalExpressInitResponseTransfer->getAddressCountryCode());

        $shippingAddressTransfer = new AddressTransfer();
        $shippingAddressTransfer->fromArray($computopPayPalExpressInitResponseTransfer->toArray(), true);
        $shippingAddressTransfer->setAddress1($computopPayPalExpressInitResponseTransfer->getAddressStreet());
        $shippingAddressTransfer->setAddress2($computopPayPalExpressInitResponseTransfer->getAddressStreet2());
        $shippingAddressTransfer->setCity($computopPayPalExpressInitResponseTransfer->getAddressCity());
        $shippingAddressTransfer->setState($computopPayPalExpressInitResponseTransfer->getAddressState());
        $shippingAddressTransfer->setZipCode($computopPayPalExpressInitResponseTransfer->getAddressZip());
        $shippingAddressTransfer->setIso2Code($computopPayPalExpressInitResponseTransfer->getAddressCountryCode());
        $shippingAddressTransfer->setCountry($countryTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->getShipment()->setShippingAddress($shippingAddressTransfer);
        }

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $expenseTransfer->getShipment()->setShippingAddress($shippingAddressTransfer);
        }

        $quoteTransfer->setShippingAddress($shippingAddressTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapBillingAddressFromComputopPayPalExpressInitResponseToQuote(
        ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer
    {
        $countryTransfer = new CountryTransfer();
        $countryTransfer->setIso2Code($computopPayPalExpressInitResponseTransfer->getAddressCountryCode());

        $billingAddressTransfer = new AddressTransfer();
        $billingAddressTransfer->fromArray($computopPayPalExpressInitResponseTransfer->toArray(), true);
        $billingAddressTransfer->setAddress1($computopPayPalExpressInitResponseTransfer->getBillingAddressStreet());
        $billingAddressTransfer->setAddress2($computopPayPalExpressInitResponseTransfer->getBillingAddressStreet2());
        $billingAddressTransfer->setZipCode($computopPayPalExpressInitResponseTransfer->getBillingAddressZip());
        $billingAddressTransfer->setCity($computopPayPalExpressInitResponseTransfer->getBillingAddressCity());
        $billingAddressTransfer->setState($computopPayPalExpressInitResponseTransfer->getBillingAddressState());
        $billingAddressTransfer->setIso2Code($computopPayPalExpressInitResponseTransfer->getBillingAddressCountryCode());
        $billingAddressTransfer->setCountry($countryTransfer);
        $billingAddressTransfer->setFirstName($computopPayPalExpressInitResponseTransfer->getBillingName());
        $billingAddressTransfer->setLastName($computopPayPalExpressInitResponseTransfer->getLastName());

        $quoteTransfer->setBillingAddress($billingAddressTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapCustomerFromComputopPayPalExpressInitResponseToQuote(
        ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->fromArray($computopPayPalExpressInitResponseTransfer->toArray(), true);
        $customerTransfer->setIsGuest(true);
        $quoteTransfer->setAcceptTermsAndConditions(true);
        $quoteTransfer->setCustomer($customerTransfer);

        return $quoteTransfer;
    }
}
