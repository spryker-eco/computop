<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\ExpressCheckout;

use Generated\Shared\Transfer\ComputopPayPalExpressPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Client\Computop\ComputopClientInterface;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Yves\Computop\Converter\ConverterInterface;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteClientInterface;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToShipmentClientInterface;
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
     * @var \SprykerEco\Client\Computop\ComputopClientInterface
     */
    protected $computopClient;

    /**
     * @var \SprykerEco\Yves\Computop\Dependency\Client\ComputopToShipmentClientInterface
     */
    protected $shipmentClient;

    /**
     * @var \SprykerEco\Yves\Computop\Mapper\Init\PrePlace\PayPalExpressToQuoteMapperInterface
     */
    protected $payPalExpressToQuoteMapper;

    /**
     * @param \SprykerEco\Yves\Computop\Handler\ComputopPaymentHandlerInterface $computopPaymentHandler
     * @param \SprykerEco\Yves\Computop\Converter\ConverterInterface $converter
     * @param \SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteClientInterface $quoteClient
     * @param \SprykerEco\Client\Computop\ComputopClientInterface $computopClient
     * @param \SprykerEco\Yves\Computop\Dependency\Client\ComputopToShipmentClientInterface $shipmentClient
     * @param \SprykerEco\Yves\Computop\Mapper\Init\PrePlace\PayPalExpressToQuoteMapperInterface $payPalExpressToQuoteMapper
     */
    public function __construct(
        ComputopPaymentHandlerInterface $computopPaymentHandler,
        ConverterInterface $converter,
        ComputopToQuoteClientInterface $quoteClient,
        ComputopClientInterface $computopClient,
        ComputopToShipmentClientInterface $shipmentClient,
        PayPalExpressToQuoteMapperInterface $payPalExpressToQuoteMapper
    ) {
        $this->computopPaymentHandler = $computopPaymentHandler;
        $this->converter = $converter;
        $this->quoteClient = $quoteClient;
        $this->computopClient = $computopClient;
        $this->shipmentClient = $shipmentClient;
        $this->payPalExpressToQuoteMapper = $payPalExpressToQuoteMapper;
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

        $responseTransfer = $this->converter->getResponseTransfer($responseArray);

        $quoteTransfer = $this->addPaymentToQuote($quoteTransfer, $responseTransfer);
        $quoteTransfer = $this->addPaymentSelectionToQuote($quoteTransfer);
        $quoteTransfer = $this->addShippingAddressToQuote($quoteTransfer);
        $quoteTransfer = $this->addBillingAddressToQuote($quoteTransfer);
        $quoteTransfer = $this->addCustomerToQuote($quoteTransfer);

        $quoteTransfer->setCheckoutConfirmed(true);

        $this->quoteClient->setQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $responseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addPaymentToQuote(QuoteTransfer $quoteTransfer, TransferInterface $responseTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getPayment()->getComputopPayPalExpress() === null) {
            $computopTransfer = new ComputopPayPalExpressPaymentTransfer();
            $quoteTransfer->getPayment()->setComputopPayPalExpress($computopTransfer);
        }

        /** @var \Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer $responseTransfer */
        $quoteTransfer->getPayment()->getComputopPayPalExpress()->setPayPalExpressInitResponse(
            $responseTransfer
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
        $payPalInitResponseTransfer = $quoteTransfer->getPayment()->getComputopPayPalExpress()->getPayPalExpressInitResponse();
        if ($payPalInitResponseTransfer->getAddressStreet() === null) {
            return $quoteTransfer;
        }

        $quoteTransfer = $this->shipmentClient->expandQuoteWithShipmentGroups($quoteTransfer);

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
        if ($quoteTransfer->getCustomer()->getIsGuest() !== null && !$quoteTransfer->getCustomer()->getIsGuest()) {
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
}
