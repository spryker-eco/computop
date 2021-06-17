<?php

namespace SprykerEco\Client\Computop\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer;
use Spryker\Client\Shipment\ShipmentClientInterface;

class ComputopToShipmentClientBridge implements ComputopToShipmentClientInterface
{
    /**
     * @var ShipmentClientInterface
     */
    protected $shipmentClient;

    /**
     * @param ShipmentClientInterface $shipmentClient
     */
    public function __construct(ShipmentClientInterface $shipmentClient)
    {
        $this->shipmentClient = $shipmentClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithShipmentGroups(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->shipmentClient->expandQuoteWithShipmentGroups($quoteTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return ShipmentMethodsCollectionTransfer
     */
    public function getAvailableMethodsByShipment(QuoteTransfer $quoteTransfer): ShipmentMethodsCollectionTransfer
    {
        return $this->shipmentClient->getAvailableMethodsByShipment($quoteTransfer);
    }


}
