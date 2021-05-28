<?php

namespace SprykerEco\Yves\Computop\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Client\ComputopApi\ComputopApiClient;

class ComputopToComputopApiClientBridge implements ComputopToComputopApiClientInterface
{
    /**
     * @var ComputopApiClient
     */
    protected $computopApiClient;

    /**
     * ComputopToComputopApiClientBridge constructor.
     */
    public function __construct($computopApiClient)
    {
        $this->computopApiClient = $computopApiClient;
    }

    public function sendPayPalExpressPrepareRequest(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->computopApiClient->sendPayPalExpressPrepareRequest($quoteTransfer);
    }


    public function sendPayPalExpressCompleteRequest(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->computopApiClient->sendPayPalExpressCompleteRequest($quoteTransfer);
    }
}
