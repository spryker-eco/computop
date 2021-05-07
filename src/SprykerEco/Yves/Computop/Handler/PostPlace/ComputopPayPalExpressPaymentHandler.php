<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\PostPlace;

use Generated\Shared\Transfer\ComputopPayPalExpressPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Quote\QuoteClientInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Client\Computop\ComputopClientInterface;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Yves\Computop\ComputopFactory;
use SprykerEco\Yves\Computop\Converter\ConverterInterface;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteClientInterface;
use SprykerEco\Yves\Computop\Mapper\Init\PrePlace\PayPalExpressToQuoteMapperInterface;

class ComputopPayPalExpressPaymentHandler extends AbstractPostPlacePaymentHandler
{
    /**
     * @var QuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var PayPalExpressToQuoteMapperInterface
     */
    private $payPalExpressToQuoteMapper;

    public function __construct(
        ConverterInterface $converter,
        ComputopClientInterface $computopClient,
        ComputopToQuoteClientInterface $quoteClient,
        PayPalExpressToQuoteMapperInterface $payPalExpressToQuoteMapper
    )
    {
        parent::__construct($converter, $computopClient);
        $this->quoteClient = $quoteClient;
        $this->payPalExpressToQuoteMapper = $payPalExpressToQuoteMapper;
    }


    public function handle(QuoteTransfer $quoteTransfer, array $responseArray)
    {
        $quoteTransfer = parent::handle($quoteTransfer, $responseArray);
        $payPalInitResponseTransfer = $quoteTransfer->getPayment()->getComputopPayPalExpress()->getPayPalExpressInitResponse();
        //magic

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
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addPaymentToQuote(QuoteTransfer $quoteTransfer, AbstractTransfer $responseTransfer)
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function saveInitResponse(QuoteTransfer $quoteTransfer)
    {
        return $this->computopClient->savePayPalExpressInitResponse($quoteTransfer);
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
