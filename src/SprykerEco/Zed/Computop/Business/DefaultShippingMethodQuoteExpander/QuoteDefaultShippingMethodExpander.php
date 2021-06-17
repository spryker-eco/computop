<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\DefaultShippingMethodQuoteExpander;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToShipmentFacadeInterface;

class QuoteDefaultShippingMethodExpander implements QuoteDefaultShippingMethodExpanderInterface
{
    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $computopConfig;

    /**
     * @var \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @param \SprykerEco\Zed\Computop\ComputopConfig $computopConfig
     * @param \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToShipmentFacadeInterface $shipmentFacade
     */
    public function __construct(ComputopConfig $computopConfig, ComputopToShipmentFacadeInterface $shipmentFacade)
    {
        $this->computopConfig = $computopConfig;
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithDefaultShippingMethod(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer->setDefaultShipmentSelected(true);
        $defaultShipmentId = $this->computopConfig->getDefaultShipmentMethodId();
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
        return $this->shipmentFacade->expandQuoteWithShipmentGroups($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected function getDefaultShipmentMethod(QuoteTransfer $quoteTransfer, int $idShipmentMethod): ShipmentMethodTransfer
    {
        $availableMethods = $this->shipmentFacade
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
