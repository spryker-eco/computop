<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\ExpressCheckout;

use Generated\Shared\Transfer\ComputopApiPayPalExpressPrepareResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerEco\Service\ComputopApi\ComputopApiServiceInterface;
use SprykerEco\Yves\Computop\ComputopConfigInterface;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToComputopApiClientInterface;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteClientInterface;

class ComputopPayPalExpressPrepareAggregator implements ComputopPayPalExpressPrepareAggregatorInterface
{
    /**
     * @var \SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    protected $stepEngineFormDataProvider;

    /**
     * @var \SprykerEco\Yves\Computop\Dependency\Client\ComputopToComputopApiClientInterface
     */
    protected $computopApiClient;

    /**
     * @var \SprykerEco\Service\ComputopApi\ComputopApiServiceInterface
     */
    protected $computopApiService;

    /**
     * @var \SprykerEco\Yves\Computop\ComputopConfigInterface
     */
    protected $computopConfig;

    /**
     * @param \SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteClientInterface $quoteClient
     * @param \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface $stepEngineFormDataProvider
     * @param \SprykerEco\Yves\Computop\Dependency\Client\ComputopToComputopApiClientInterface $computopApiClient
     * @param \SprykerEco\Service\ComputopApi\ComputopApiServiceInterface $computopApiService
     * @param \SprykerEco\Yves\Computop\ComputopConfigInterface $computopConfig
     */
    public function __construct(
        ComputopToQuoteClientInterface $quoteClient,
        StepEngineFormDataProviderInterface $stepEngineFormDataProvider,
        ComputopToComputopApiClientInterface $computopApiClient,
        ComputopApiServiceInterface $computopApiService,
        ComputopConfigInterface $computopConfig
    ) {
        $this->quoteClient = $quoteClient;
        $this->stepEngineFormDataProvider = $stepEngineFormDataProvider;
        $this->computopApiClient = $computopApiClient;
        $this->computopApiService = $computopApiService;
        $this->computopConfig = $computopConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiPayPalExpressPrepareResponseTransfer
     */
    public function aggregate(QuoteTransfer $quoteTransfer): ComputopApiPayPalExpressPrepareResponseTransfer
    {
        $quoteTransfer = $this->stepEngineFormDataProvider->getData($quoteTransfer);
        $quoteTransfer = $this->computopApiClient->sendPayPalExpressPrepareRequest($quoteTransfer);

        $this->quoteClient->setQuote($quoteTransfer);

        return $quoteTransfer->getPayment()->getComputopPayPalExpress()->getPayPalExpressPrepareResponse();
    }
}
