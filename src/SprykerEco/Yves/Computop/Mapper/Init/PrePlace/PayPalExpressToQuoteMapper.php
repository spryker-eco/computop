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
     * @param \Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapAddressFromComputopPayPalExpressInitResponseToQuote(
        ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        $countryTransfer = new CountryTransfer();
        $countryTransfer->setIso2Code($computopPayPalExpressInitResponseTransfer->getAddressCountryCode());

        $shippingAddressTransfer = (new AddressTransfer())
            ->fromArray($computopPayPalExpressInitResponseTransfer->toArray(), true)
            ->setAddress1($computopPayPalExpressInitResponseTransfer->getAddressStreet())
            ->setAddress2($computopPayPalExpressInitResponseTransfer->getAddressStreet2())
            ->setCity($computopPayPalExpressInitResponseTransfer->getAddressCity())
            ->setState($computopPayPalExpressInitResponseTransfer->getAddressState())
            ->setZipCode($computopPayPalExpressInitResponseTransfer->getAddressZip())
            ->setIso2Code($computopPayPalExpressInitResponseTransfer->getAddressCountryCode())
            ->setCountry($countryTransfer);

        $this->mapShippingAddressToQuoteItems($shippingAddressTransfer, $quoteTransfer);
        $this->mapShippingAddressToQuoteExpenses($shippingAddressTransfer, $quoteTransfer);

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
    ): QuoteTransfer {
        $countryTransfer = new CountryTransfer();
        $countryTransfer->setIso2Code($computopPayPalExpressInitResponseTransfer->getAddressCountryCode());

        $billingAddressTransfer = (new AddressTransfer())
            ->fromArray($computopPayPalExpressInitResponseTransfer->toArray(), true)
            ->setAddress1($computopPayPalExpressInitResponseTransfer->getBillingAddressStreet())
            ->setAddress2($computopPayPalExpressInitResponseTransfer->getBillingAddressStreet2())
            ->setZipCode($computopPayPalExpressInitResponseTransfer->getBillingAddressZip())
            ->setCity($computopPayPalExpressInitResponseTransfer->getBillingAddressCity())
            ->setState($computopPayPalExpressInitResponseTransfer->getBillingAddressState())
            ->setIso2Code($computopPayPalExpressInitResponseTransfer->getBillingAddressCountryCode())
            ->setCountry($countryTransfer)
            ->setFirstName($computopPayPalExpressInitResponseTransfer->getBillingName())
            ->setLastName($computopPayPalExpressInitResponseTransfer->getLastName());

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
    ): QuoteTransfer {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->fromArray($computopPayPalExpressInitResponseTransfer->toArray(), true);
        $customerTransfer->setIsGuest(true);
        $quoteTransfer->setAcceptTermsAndConditions(true);
        $quoteTransfer->setCustomer($customerTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $shippingAddressTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mapShippingAddressToQuoteItems(AddressTransfer $shippingAddressTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->getShipment()->setShippingAddress($shippingAddressTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $shippingAddressTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mapShippingAddressToQuoteExpenses(AddressTransfer $shippingAddressTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $expenseTransfer->getShipment()->setShippingAddress($shippingAddressTransfer);
        }

        return $quoteTransfer;
    }
}
