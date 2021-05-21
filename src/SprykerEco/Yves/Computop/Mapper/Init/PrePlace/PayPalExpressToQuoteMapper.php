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

class PayPalExpressToQuoteMapper implements PayPalExpressToQuoteMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapAddressTransfer(
        QuoteTransfer $quoteTransfer,
        ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
    ): QuoteTransfer {
        $shippingAddressTransfer = new AddressTransfer();
        $countryTransfer = new CountryTransfer();
        $countryTransfer->setIso2Code($computopPayPalExpressInitResponseTransfer->getAddressCountryCode());
        $shippingAddressTransfer->setFirstName($computopPayPalExpressInitResponseTransfer->getFirstName());
        $shippingAddressTransfer->setLastName($computopPayPalExpressInitResponseTransfer->getLastName());
        $shippingAddressTransfer->setAddress1($computopPayPalExpressInitResponseTransfer->getAddressStreet());
        $shippingAddressTransfer->setAddress2($computopPayPalExpressInitResponseTransfer->getAddressStreet2());
        $shippingAddressTransfer->setCity($computopPayPalExpressInitResponseTransfer->getAddressCity());
        $shippingAddressTransfer->setState($computopPayPalExpressInitResponseTransfer->getAddressState());
        $shippingAddressTransfer->setZipCode($computopPayPalExpressInitResponseTransfer->getAddressZip());
        $shippingAddressTransfer->setIso2Code($computopPayPalExpressInitResponseTransfer->getAddressCountryCode());
        $shippingAddressTransfer->setCountry($countryTransfer);
        $shippingAddressTransfer->setPhone($computopPayPalExpressInitResponseTransfer->getPhone());

        foreach ($quoteTransfer->getItems() as $item) {
            $item->getShipment()->setShippingAddress($shippingAddressTransfer);
        }

        foreach ($quoteTransfer->getExpenses() as $expense) {
            $expense->getShipment()->setShippingAddress($shippingAddressTransfer);
        }

        $quoteTransfer->setShippingAddress($shippingAddressTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapBillingTransfer(
        QuoteTransfer $quoteTransfer,
        ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
    ): QuoteTransfer {
        $billingAddressTransfer = new AddressTransfer();
        $countryTransfer = new CountryTransfer();
        $countryTransfer->setIso2Code($computopPayPalExpressInitResponseTransfer->getAddressCountryCode());
        $billingAddressTransfer->setFirstName($computopPayPalExpressInitResponseTransfer->getFirstName());
        $billingAddressTransfer->setLastName($computopPayPalExpressInitResponseTransfer->getLastName());
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapCustomerTransfer(
        QuoteTransfer $quoteTransfer,
        ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
    ): QuoteTransfer {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setFirstName($computopPayPalExpressInitResponseTransfer->getFirstName());
        $customerTransfer->setLastName($computopPayPalExpressInitResponseTransfer->getLastName());
        $customerTransfer->setEmail($computopPayPalExpressInitResponseTransfer->getEmail());
        $customerTransfer->setIsGuest(true);
        $quoteTransfer->setAcceptTermsAndConditions(true);
        $quoteTransfer->setCustomer($customerTransfer);

        return $quoteTransfer;
    }
}
