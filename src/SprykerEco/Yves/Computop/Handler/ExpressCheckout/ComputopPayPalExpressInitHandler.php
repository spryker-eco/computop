<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\ExpressCheckout;

use Generated\Shared\Transfer\ComputopPayPalExpressPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Quote\QuoteClientInterface;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Yves\Computop\ComputopFactory;
use SprykerEco\Yves\Computop\Converter\ConverterInterface;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteClientInterface;
use SprykerEco\Yves\Computop\Handler\ComputopPrePostPaymentHandlerInterface;
use SprykerEco\Yves\Computop\Mapper\Init\PrePlace\PayPalExpressToQuoteMapperInterface;

class ComputopPayPalExpressInitHandler implements ComputopPayPalExpressInitHandlerInterface
{
    /**
     * @var QuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var PayPalExpressToQuoteMapperInterface
     */
    private $payPalExpressToQuoteMapper;

    /**
     * @var ConverterInterface
     */
    private $converter;

    public function __construct(
        ConverterInterface $converter,
        ComputopToQuoteClientInterface $quoteClient,
        PayPalExpressToQuoteMapperInterface $payPalExpressToQuoteMapper
    )
    {
        $this->quoteClient = $quoteClient;
        $this->payPalExpressToQuoteMapper = $payPalExpressToQuoteMapper;
        $this->converter = $converter;
    }

    public function handle(QuoteTransfer $quoteTransfer, array $responseArray)
    {
        $responseTransfer = $this->converter->getResponseTransfer($responseArray);
        $this->addPaymentToQuote($quoteTransfer, $responseTransfer);
        //fill the quote
        //fill address
        $quoteTransfer = $this->handleShippingAddress($quoteTransfer);
        $quoteTransfer = $this->handleBillingAddress($quoteTransfer);
        //fill customer
        $quoteTransfer = $this->handleCustomer($quoteTransfer);
        //fill payment
        $quoteTransfer = $this->handlePaymentSelection($quoteTransfer);

        $quoteTransfer->setCheckoutConfirmed(true);


        //place order


        //confirm order

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

        $quoteTransfer->getPayment()->getComputopPayPalExpress()->setPayPalExpressInitResponse(
            $responseTransfer
        );

        return $quoteTransfer;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return QuoteTransfer
     */
    protected function handleShippingAddress(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $payPalInitResponseTransfer = $quoteTransfer->getPayment()->getComputopPayPalExpress()->getPayPalExpressInitResponse();
        if ($payPalInitResponseTransfer->getAddressStreet() === null) {
            return $quoteTransfer;
        }

        $quoteTransfer = (new \SprykerEco\Client\Computop\ComputopFactory())->getShipmentClient()->expandQuoteWithShipmentGroups($quoteTransfer);

        return $this->payPalExpressToQuoteMapper->mapAddressTransfer($quoteTransfer, $payPalInitResponseTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return QuoteTransfer
     */
    protected function handleBillingAddress(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $payPalInitResponseTransfer = $quoteTransfer->getPayment()->getComputopPayPalExpress()->getPayPalExpressInitResponse();

        if ($payPalInitResponseTransfer->getBillingAddressStreet() === null) {
            return $quoteTransfer;
        }

        return $this->payPalExpressToQuoteMapper->mapBillingTransfer($quoteTransfer, $payPalInitResponseTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return QuoteTransfer
     */
    protected function handleCustomer(QuoteTransfer $quoteTransfer): ?QuoteTransfer
    {
        if (!$quoteTransfer->getCustomer()->getIsGuest()) {
            return $quoteTransfer;
        }

        $payPalInitResponseTransfer = $quoteTransfer->getPayment()->getComputopPayPalExpress()->getPayPalExpressInitResponse();

        return $this->payPalExpressToQuoteMapper->mapCustomerTransfer($quoteTransfer, $payPalInitResponseTransfer);
    }

    protected function handlePaymentSelection(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->getPayment()->setPaymentSelection(ComputopConfig::PAYMENT_METHOD_PAY_PAL_EXPRESS);

        $handler = (new ComputopFactory())->createComputopPaymentHandler();

        return $handler->addPaymentToQuote($quoteTransfer);
    }
}
