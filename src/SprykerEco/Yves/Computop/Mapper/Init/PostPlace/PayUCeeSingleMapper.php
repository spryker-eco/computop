<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Router\Router\Router;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Shared\ComputopApi\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin;

class PayUCeeSingleMapper extends AbstractMapper
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer
     */
    public function createComputopPaymentTransfer(QuoteTransfer $quoteTransfer): ComputopPayuCeeSinglePaymentTransfer
    {
        /** @var \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer $computopPaymentTransfer */
        $computopPaymentTransfer = parent::createComputopPaymentTransfer($quoteTransfer);

        if ($this->getShippingAddress($quoteTransfer)) {
            $this->addShippingData($computopPaymentTransfer, $this->getShippingAddress($quoteTransfer));
        }

        $computopPaymentTransfer->setMac(
            $this->computopApiService->generateEncryptedMac($this->createRequestTransfer($computopPaymentTransfer))
        );

        $computopPaymentTransfer->setPayType(ComputopApiConfig::PAYU_CEE_DEFAULT_PAY_TYPE);

        $computopPaymentTransfer->setOrderDesc(
            $this->computopApiService->getDescriptionValue($quoteTransfer->getItems()->getArrayCopy())
        );

        if ($quoteTransfer->getItems()->count()) {
            $computopPaymentTransfer->setArticleList($this->getArticleList($quoteTransfer->getItems()));
        }

        if ($quoteTransfer->getCustomer()) {
            $this->setCustomerData($computopPaymentTransfer, $quoteTransfer->getCustomer());
        }

        $computopPaymentTransfer->setCapture(
            $this->getCaptureType(ComputopSharedConfig::PAYMENT_METHOD_PAYU_CEE_SINGLE)
        );

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(QuoteTransfer $quoteTransfer): ComputopPayuCeeSinglePaymentTransfer
    {
        $computopPaymentTransfer = new ComputopPayuCeeSinglePaymentTransfer();

        $computopPaymentTransfer->setTransId($this->generateTransId($quoteTransfer));
        $computopPaymentTransfer->setUrlSuccess(
            $this->router->generate(ComputopRouteProviderPlugin::PAYU_CEE_SINGLE_SUCCESS, [], Router::ABSOLUTE_URL)
        );

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    private function getShippingAddress(QuoteTransfer $quoteTransfer): ?AddressTransfer
    {
        if ($quoteTransfer->getBillingSameAsShipping()) {
            $address = $quoteTransfer->getBillingAddress();
        } elseif ($quoteTransfer->getItems()->count()) {
            $item = current($quoteTransfer->getItems());
            $address = $item->getShipment()->getShippingAddress();
        }

        return $address ?? null;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return void
     */
    private function addShippingData(ComputopPayuCeeSinglePaymentTransfer $paymentTransfer, AddressTransfer $addressTransfer)
    {
        $paymentTransfer->setSdName($addressTransfer->getFirstName() . ' ' . $addressTransfer->getLastName());
        $paymentTransfer->setSdEmail($addressTransfer->getEmail());
        $paymentTransfer->setSdPhone($addressTransfer->getPhone());
        $paymentTransfer->setSdZIP($addressTransfer->getZipCode());
        $paymentTransfer->setSdCountryCode($addressTransfer->getIso2Code());
        $paymentTransfer->setSdState($addressTransfer->getState());
        $paymentTransfer->setSdCity($addressTransfer->getCity());
        $paymentTransfer->setSdStreet($addressTransfer->getAddress1());
        $paymentTransfer->setSdAddressAddition($addressTransfer->getAddress2());
        $paymentTransfer->setSdPOBox($addressTransfer->getPoBox());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return string
     */
    private function getArticleList($items): string
    {
        $out = [];
        foreach ($items as $item) {
            $out[] = implode(',', [
                str_replace([',', '+'], ' ', $item->getName()),
                $item->getUnitPrice(),
                $item->getQuantity(),
            ]);
        }

        return implode('+', $out);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     *
     * @return void
     */
    private function setCustomerData(ComputopPayuCeeSinglePaymentTransfer $paymentTransfer, CustomerTransfer $customer)
    {
        $paymentTransfer->setFirstName($customer->getFirstName());
        $paymentTransfer->setLastName($customer->getLastName());
        $paymentTransfer->setEmail($customer->getEmail());
        $paymentTransfer->setPhone($customer->getPhone());
    }
}
