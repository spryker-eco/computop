<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Computop\Quote\Dependency\Plugin;

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
     * {@inheritDoc}
     * - Expands provided `QuoteTransfer` with default shipping method.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addShipmentToQuoteItems(QuoteTransfer $quoteTransfer, ShipmentTransfer $shipmentTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setShipment($shipmentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function expandQuoteWithShipmentGroups(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()->getShipmentClient()->expandQuoteWithShipmentGroups($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
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
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function initItemShipment(int $defaultShipmentId): ShipmentTransfer
    {
        $itemShipmentTransfer = new ShipmentTransfer();
        $itemShipmentTransfer->setShipmentSelection($defaultShipmentId);
        $itemShipmentTransfer->setShippingAddress(new AddressTransfer());

        return $itemShipmentTransfer;
    }
}
