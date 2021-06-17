<?php

namespace SprykerEco\Yves\Computop\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;
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
}
