<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

class ComputopToComputopApiClientBridge implements ComputopToComputopApiClientInterface
{
    /**
     * @var \SprykerEco\Client\ComputopApi\ComputopApiClientInterface
     */
    protected $computopApiClient;

    /**
     * @param \SprykerEco\Client\ComputopApi\ComputopApiClientInterface $computopApiClient
     */
    public function __construct($computopApiClient)
    {
        $this->computopApiClient = $computopApiClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function sendPayPalExpressPrepareRequest(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->computopApiClient->sendPayPalExpressPrepareRequest($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function sendPayPalExpressCompleteRequest(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->computopApiClient->sendPayPalExpressCompleteRequest($quoteTransfer);
    }
}
