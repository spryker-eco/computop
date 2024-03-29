<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\ExpressCheckout;

use Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer;
use Generated\Shared\Transfer\ComputopPayPalExpressPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Yves\Computop\Converter\ConverterInterface;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteClientInterface;
use SprykerEco\Yves\Computop\Handler\ComputopPaymentHandlerInterface;
use SprykerEco\Yves\Computop\Mapper\Init\PrePlace\PayPalExpressToQuoteMapperInterface;

class ComputopPayPalExpressInitAggregator implements ComputopPayPalExpressInitAggregatorInterface
{
    /**
     * @var \SprykerEco\Yves\Computop\Handler\ComputopPaymentHandlerInterface
     */
    protected $computopPaymentHandler;

    /**
     * @var \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    protected $converter;

    /**
     * @var \SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \SprykerEco\Yves\Computop\Mapper\Init\PrePlace\PayPalExpressToQuoteMapperInterface
     */
    protected $payPalExpressToQuoteMapper;

    /**
     * @var array<\SprykerEco\Yves\ComputopExtension\Dependency\Plugin\PayPalExpressInitQuoteExpanderPluginInterface>
     */
    protected $payPalExpressInitQuoteExpanderPlugins;

    /**
     * @param \SprykerEco\Yves\Computop\Handler\ComputopPaymentHandlerInterface $computopPaymentHandler
     * @param \SprykerEco\Yves\Computop\Converter\ConverterInterface $converter
     * @param \SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteClientInterface $quoteClient
     * @param \SprykerEco\Yves\Computop\Mapper\Init\PrePlace\PayPalExpressToQuoteMapperInterface $payPalExpressToQuoteMapper
     * @param array<\SprykerEco\Yves\ComputopExtension\Dependency\Plugin\PayPalExpressInitQuoteExpanderPluginInterface> $payPalExpressInitQuoteExpanderPlugins
     */
    public function __construct(
        ComputopPaymentHandlerInterface $computopPaymentHandler,
        ConverterInterface $converter,
        ComputopToQuoteClientInterface $quoteClient,
        PayPalExpressToQuoteMapperInterface $payPalExpressToQuoteMapper,
        array $payPalExpressInitQuoteExpanderPlugins
    ) {
        $this->computopPaymentHandler = $computopPaymentHandler;
        $this->converter = $converter;
        $this->quoteClient = $quoteClient;
        $this->payPalExpressToQuoteMapper = $payPalExpressToQuoteMapper;
        $this->payPalExpressInitQuoteExpanderPlugins = $payPalExpressInitQuoteExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $responseArray
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function aggregate(QuoteTransfer $quoteTransfer, array $responseArray): QuoteTransfer
    {
        $quoteTransfer->requirePayment();

        /** @var \Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer $responseTransfer */
        $responseTransfer = $this->converter->getResponseTransfer($responseArray);

        $quoteTransfer = $this->addPaymentToQuote($quoteTransfer, $responseTransfer);
        $quoteTransfer = $this->addPaymentSelectionToQuote($quoteTransfer);
        $quoteTransfer = $this->addShippingAddressToQuote($quoteTransfer);
        $quoteTransfer = $this->addBillingAddressToQuote($quoteTransfer);
        $quoteTransfer = $this->addCustomerToQuote($quoteTransfer);

        $quoteTransfer->setCheckoutConfirmed(true);

        $quoteTransfer = $this->executePayPalExpressInitQuoteExpanderPlugins($quoteTransfer);

        $this->quoteClient->setQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addPaymentToQuote(QuoteTransfer $quoteTransfer, ComputopPayPalExpressInitResponseTransfer $responseTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getPaymentOrFail()->getComputopPayPalExpress() === null) {
            $computopTransfer = new ComputopPayPalExpressPaymentTransfer();
            $quoteTransfer->getPaymentOrFail()->setComputopPayPalExpress($computopTransfer);
        }

        $quoteTransfer->getPaymentOrFail()->getComputopPayPalExpressOrFail()->setPayPalExpressInitResponse(
            $responseTransfer,
        );

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addShippingAddressToQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $payPalInitResponseTransfer = $quoteTransfer->getPaymentOrFail()
            ->getComputopPayPalExpressOrFail()
            ->getPayPalExpressInitResponseOrFail();

        if ($payPalInitResponseTransfer->getAddressStreet() === null) {
            return $quoteTransfer;
        }

        return $this
            ->payPalExpressToQuoteMapper
            ->mapAddressFromComputopPayPalExpressInitResponseToQuote($payPalInitResponseTransfer, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addBillingAddressToQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $payPalInitResponseTransfer = $quoteTransfer
            ->getPayment()
            ->getComputopPayPalExpress()->getPayPalExpressInitResponse();

        if ($payPalInitResponseTransfer->getBillingAddressStreet() === null) {
            $quoteTransfer->setBillingAddress($quoteTransfer->getShippingAddress());

            return $quoteTransfer->setBillingSameAsShipping(true);
        }

        return $this->payPalExpressToQuoteMapper->mapBillingAddressFromComputopPayPalExpressInitResponseToQuote($payPalInitResponseTransfer, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addCustomerToQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getCustomer()->getIdCustomer() !== null) {
            return $quoteTransfer;
        }

        $payPalInitResponseTransfer = $quoteTransfer->getPayment()
            ->getComputopPayPalExpress()
            ->getPayPalExpressInitResponse();

        return $this->payPalExpressToQuoteMapper->mapCustomerFromComputopPayPalExpressInitResponseToQuote($payPalInitResponseTransfer, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addPaymentSelectionToQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer->getPayment()->setPaymentSelection(ComputopConfig::PAYMENT_METHOD_PAY_PAL_EXPRESS);

        return $this->computopPaymentHandler->addPaymentToQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executePayPalExpressInitQuoteExpanderPlugins(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($this->payPalExpressInitQuoteExpanderPlugins as $payPalExpressInitQuoteExpanderPlugin) {
            $quoteTransfer = $payPalExpressInitQuoteExpanderPlugin->expand($quoteTransfer);
        }

        return $quoteTransfer;
    }
}
