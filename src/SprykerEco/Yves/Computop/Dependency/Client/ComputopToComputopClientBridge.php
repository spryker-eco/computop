<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Dependency\Client;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ComputopToComputopClientBridge implements ComputopToComputopClientInterface
{
    /**
     * @var \SprykerEco\Client\Computop\ComputopClientInterface
     */
    protected $computopClient;

    /**
     * @param \SprykerEco\Client\Computop\ComputopClientInterface $computopClient
     */
    public function __construct($computopClient)
    {
        $this->computopClient = $computopClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $cardPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function logResponse(ComputopResponseHeaderTransfer $cardPaymentTransfer)
    {
        return $this->computopClient->logResponse($cardPaymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveSofortResponse(QuoteTransfer $quoteTransfer)
    {
        return $this->computopClient->saveSofortResponse($quoteTransfer);
    }
}
