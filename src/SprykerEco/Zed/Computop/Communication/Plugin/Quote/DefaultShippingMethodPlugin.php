<?php

namespace SprykerEco\Zed\Computop\Communication\Plugin\Quote;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Quote\Dependency\Plugin\QuoteTransferExpanderPluginInterface;

/**
 * @method \SprykerEco\Client\Computop\ComputopFactory getFactory()
 */
class DefaultShippingMethodPlugin extends AbstractPlugin implements QuoteTransferExpanderPluginInterface
{
    /**
     * @param QuoteTransfer $quoteTransfer
     * @return QuoteTransfer
     */
    public function expandQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (count($quoteTransfer->getItems()) === 0 || $this->quoteAlreadyHasShipment($quoteTransfer)) {
            return $quoteTransfer;
        }

        $defaultShipmentId = $this->getFactory()->getConfig()->getDefaultShipmentMethodId();
        $itemShipmentTransfer = $this->initItemShipment($defaultShipmentId);
        $this->addShipmentToQuoteItems($quoteTransfer, $itemShipmentTransfer);
        $defaultShipmentMethodTransfer = $this->getDefaultShipmentMethod($quoteTransfer, $defaultShipmentId);
        $itemShipmentTransfer->setMethod($defaultShipmentMethodTransfer);

        $quoteTransfer = $this->addShipmentToQuoteItems($quoteTransfer, $itemShipmentTransfer);
        $quoteTransfer = $this->expandQuoteWithShipmentGroups($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param ShipmentTransfer $shipmentTransfer
     *
     * @return QuoteTransfer
     */
    protected function addShipmentToQuoteItems(QuoteTransfer $quoteTransfer, ShipmentTransfer $shipmentTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setShipment($shipmentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return QuoteTransfer
     */
    protected function expandQuoteWithShipmentGroups(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()->getShipmentClient()->expandQuoteWithShipmentGroups($quoteTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function quoteAlreadyHasShipment(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemShipmentTransfer = $itemTransfer->getShipment();
            if ($itemShipmentTransfer !== null) {
                //at least one item already has shipment method
                return true;
            }
        }

        return false;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param int $idShipmentMethod
     *
     * @return ShipmentMethodTransfer
     */
    protected function getDefaultShipmentMethod(QuoteTransfer $quoteTransfer, int $idShipmentMethod): ShipmentMethodTransfer
    {
        $availableMethods = $this->getFactory()
            ->getShipmentClient()
            ->getAvailableMethodsByShipment($quoteTransfer)
            ->getShipmentMethods();
        foreach ($availableMethods as $availableMethod) {
            foreach ($availableMethod->getMethods() as $method) {
                if ($method->getIdShipmentMethod() === $idShipmentMethod) {
                    return $method;
                }
            }
        }

        return new ShipmentMethodTransfer();
    }

    /**
     * @param int $defaultShipmentId
     *
     * @return ShipmentTransfer
     */
    protected function initItemShipment(int $defaultShipmentId): ShipmentTransfer
    {
        $itemShipmentTransfer = new ShipmentTransfer();
        $itemShipmentTransfer->setShipmentSelection($defaultShipmentId);
        $itemShipmentTransfer->setShippingAddress(new AddressTransfer());

        return $itemShipmentTransfer;
    }
}
