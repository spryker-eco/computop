<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\ExpressCheckout;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Client\Computop\ComputopClientInterface;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToComputopApiClientInterface;

class ComputopPayPalExpressCompleteHandler implements ComputopPayPalExpressCompleteHandlerInterface
{
    /**
     * @var ComputopToComputopApiClientInterface
     */
    protected $computopApiClient;

    /**
     * @var \SprykerEco\Client\Computop\ComputopClientInterface
     */
    protected $computopClient;


    /**
     * @param ComputopToComputopApiClientInterface $computopApiClient
     * @param ComputopClientInterface $computopClient
     */
    public function __construct(
        ComputopToComputopApiClientInterface $computopApiClient,
        ComputopClientInterface $computopClient
    ) {
        $this->computopApiClient = $computopApiClient;
        $this->computopClient = $computopClient;
    }

    /**
     * @inheritDoc
     */
    public function handle(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer = $this->computopApiClient->sendPayPalExpressCompleteRequest($quoteTransfer);
        $quoteTransfer = $this->computopClient->savePayPalExpressInitResponse($quoteTransfer);
        $quoteTransfer = $this->computopClient->savePayPalExpressCompleteResponse($quoteTransfer);

        return $quoteTransfer;
    }
}
