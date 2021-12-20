<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\PostPlace;

use Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Client\Computop\ComputopClientInterface;
use SprykerEco\Yves\Computop\Converter\ConverterInterface;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToCalculationClientInterface;

class ComputopEasyCreditPaymentHandler extends AbstractPostPlacePaymentHandler
{
    /**
     * @var \SprykerEco\Yves\Computop\Dependency\Client\ComputopToCalculationClientInterface
     */
    protected $calculationClient;

    /**
     * @param \SprykerEco\Yves\Computop\Converter\ConverterInterface $converter
     * @param \SprykerEco\Client\Computop\ComputopClientInterface $computopClient
     * @param \SprykerEco\Yves\Computop\Dependency\Client\ComputopToCalculationClientInterface $calculationClient
     */
    public function __construct(
        ConverterInterface $converter,
        ComputopClientInterface $computopClient,
        ComputopToCalculationClientInterface $calculationClient
    ) {
        parent::__construct($converter, $computopClient);
        $this->calculationClient = $calculationClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $responseArray
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handle(QuoteTransfer $quoteTransfer, array $responseArray): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\ComputopEasyCreditInitResponseTransfer $responseTransfer */
        $responseTransfer = $this->converter->getResponseTransfer($responseArray);
        $quoteTransfer = $this->addPaymentToQuote($quoteTransfer, $responseTransfer);

        $this->computopClient->logResponse($responseTransfer->getHeader());

        $quoteTransfer->getPayment()->getComputopEasyCredit()->fromArray(
            $quoteTransfer->getPayment()->getComputopEasyCredit()->getEasyCreditInitResponse()->getHeader()->toArray(),
            true,
        );

        $quoteTransfer = $this->computopClient->easyCreditStatusApiCall($quoteTransfer);

        return $this->calculationClient->recalculate($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ComputopEasyCreditInitResponseTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addPaymentToQuote(QuoteTransfer $quoteTransfer, AbstractTransfer $responseTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getPayment()->getComputopEasyCredit() === null) {
            $computopTransfer = new ComputopEasyCreditPaymentTransfer();
            $quoteTransfer->getPayment()->setComputopEasyCredit($computopTransfer);
        }

        $quoteTransfer->getPayment()->getComputopEasyCredit()->setEasyCreditInitResponse(
            $responseTransfer,
        );

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function saveInitResponse(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->computopClient->saveEasyCreditInitResponse($quoteTransfer);
    }
}
