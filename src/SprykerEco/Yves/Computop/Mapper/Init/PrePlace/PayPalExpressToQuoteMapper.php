<?php

namespace SprykerEco\Yves\Computop\Mapper\Init\PrePlace;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class PayPalExpressToQuoteMapper implements PayPalExpressToQuoteMapperInterface
{
    /**
     * @param QuoteTransfer $quoteTransfer
     * @param ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
     *
     * @return QuoteTransfer
     */
    public function mapAddressTransfer(
        QuoteTransfer $quoteTransfer,
        ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
    ): QuoteTransfer
    {
        $shippingAddressTransfer = new AddressTransfer();
        $shippingAddressTransfer->setFirstName($computopPayPalExpressInitResponseTransfer->getFirstName());
        $shippingAddressTransfer->setLastName($computopPayPalExpressInitResponseTransfer->getLastName());
        $shippingAddressTransfer->setAddress1($computopPayPalExpressInitResponseTransfer->getAddressStreet());
        $shippingAddressTransfer->setAddress2($computopPayPalExpressInitResponseTransfer->getAddressStreet2());
        $shippingAddressTransfer->setCity($computopPayPalExpressInitResponseTransfer->getAddressCity());
        $shippingAddressTransfer->setState($computopPayPalExpressInitResponseTransfer->getAddressState());
        $shippingAddressTransfer->setZipCode($computopPayPalExpressInitResponseTransfer->getAddressZip());
        $shippingAddressTransfer->setIso2Code($computopPayPalExpressInitResponseTransfer->getAddressCountryCode());
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
     * @param QuoteTransfer $quoteTransfer
     * @param ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
     *
     * @return QuoteTransfer
     */
    public function mapBillingTransfer(
        QuoteTransfer $quoteTransfer,
        ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
    ): QuoteTransfer
    {
        $billingAddressTransfer = new AddressTransfer();
        $billingAddressTransfer->setFirstName($computopPayPalExpressInitResponseTransfer->getFirstName());
        $billingAddressTransfer->setLastName($computopPayPalExpressInitResponseTransfer->getLastName());
        $billingAddressTransfer->setAddress1($computopPayPalExpressInitResponseTransfer->getBillingAddressStreet());
        $billingAddressTransfer->setAddress2($computopPayPalExpressInitResponseTransfer->getBillingAddressStreet2());
        $billingAddressTransfer->setCity($computopPayPalExpressInitResponseTransfer->getBillingAddressCity());
        $billingAddressTransfer->setState($computopPayPalExpressInitResponseTransfer->getBillingAddressState());
        $billingAddressTransfer->setIso2Code($computopPayPalExpressInitResponseTransfer->getBillingAddressCountryCode());
        $billingAddressTransfer->setFirstName($computopPayPalExpressInitResponseTransfer->getBillingName());
        $quoteTransfer->setBillingAddress($billingAddressTransfer);

        return $quoteTransfer;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
     *
     * @return QuoteTransfer
     */
    public function mapCustomerTransfer(
        QuoteTransfer $quoteTransfer,
        ComputopPayPalExpressInitResponseTransfer $computopPayPalExpressInitResponseTransfer
    ): QuoteTransfer
    {
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
