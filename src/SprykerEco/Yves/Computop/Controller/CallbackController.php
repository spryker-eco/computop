<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Controller;

use Spryker\Shared\Config\Config;
use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Yves\Computop\Converter\ConverterInterface;
use SprykerEco\Yves\Computop\Handler\PrePlace\ComputopPaymentHandlerInterface;

/**
 * @method \SprykerEco\Yves\Computop\ComputopFactory getFactory()
 */
class CallbackController extends AbstractController
{
    /**
     * @var \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected $orderResponseTransfer;

    /**
     * @var \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    protected $orderResponseHeaderTransfer;

    /**
     * @var array
     */
    protected $decryptedArray;

    /**
     * @return void
     */
    public function initialize()
    {
        $responseArray = $this->getApplication()['request']->query->all();
        $this->decryptedArray = $this
            ->getFactory()
            ->getComputopService()
            ->getDecryptedArray($responseArray, Config::get(ComputopConstants::BLOWFISH_PASSWORD));

        $this->orderResponseHeaderTransfer = $this->getFactory()->getComputopService()->extractHeader(
            $this->decryptedArray,
            ComputopConfig::ORDER_METHOD
        );

        $this->getFactory()->getComputopClient()->logResponse($this->orderResponseHeaderTransfer);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successCreditCardAction()
    {
        $this->orderResponseTransfer = $this->getOrderResponseTransfer(
            $this->getFactory()->createOrderCreditCardConverter()
        );

        return $this->successAction($this->getFactory()->createCreditCardPaymentHandler());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successPayPalAction()
    {
        $this->orderResponseTransfer = $this->getOrderResponseTransfer(
            $this->getFactory()->createOrderPayPalConverter()
        );

        return $this->successAction($this->getFactory()->createPayPalPaymentHandler());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successDirectDebitAction()
    {
        $this->orderResponseTransfer = $this->getOrderResponseTransfer(
            $this->getFactory()->createOrderDirectDebitConverter()
        );

        return $this->successAction($this->getFactory()->createDirectDebitPaymentHandler());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successPaydirektAction()
    {
        $this->orderResponseTransfer = $this->getOrderResponseTransfer(
            $this->getFactory()->createOrderPaydirektConverter()
        );

        $handler = $this->getFactory()->createPaydirektPaymentHandler();

        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();

        $quoteTransfer = $handler->addPaymentToQuote(
            $quoteTransfer,
            $this->orderResponseTransfer
        );

        $this->getFactory()->getComputopClient()->savePaydirektResponse($quoteTransfer);

        if (!$quoteTransfer->getCustomer()) {
            //Todo: add translation
            $this->addSuccessMessage('Your order has been placed successfully! Thank you for shopping with us!');
        }

        return $this->redirectResponseInternal($this->getFactory()->getComputopConfig()->getCallbackSuccessCaptureRedirectPath());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successSofortAction()
    {
        $this->orderResponseTransfer = $this->getOrderResponseTransfer(
            $this->getFactory()->createOrderSofortConverter()
        );

        $handler = $this->getFactory()->createSofortPaymentHandler();

        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();

        $quoteTransfer = $handler->addPaymentToQuote(
            $quoteTransfer,
            $this->orderResponseTransfer
        );

        $this->getFactory()->getComputopClient()->saveSofortResponse($quoteTransfer);

        if (!$quoteTransfer->getCustomer()) {
            //Todo: add translation
            $this->addSuccessMessage('Your order has been placed successfully! Thank you for shopping with us!');
        }

        return $this->redirectResponseInternal($this->getFactory()->getComputopConfig()->getCallbackSuccessCaptureRedirectPath());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function failureAction()
    {
        $this->addErrorMessage($this->getErrorMessageText());

        return $this->redirectResponseInternal($this->getFactory()->getComputopConfig()->getCallbackFailureRedirectPath());
    }

    /**
     * @param \SprykerEco\Yves\Computop\Handler\PrePlace\ComputopPaymentHandlerInterface $handler
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function successAction(ComputopPaymentHandlerInterface $handler)
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $quoteTransfer = $handler->addPaymentToQuote(
            $quoteTransfer,
            $this->orderResponseTransfer
        );

        if (!$quoteTransfer->getCustomer()) {
            $this->addErrorMessage('Please login and try again');
        }

        $this->getFactory()->getQuoteClient()->setQuote($quoteTransfer);

        return $this->redirectResponseInternal($this->getFactory()->getComputopConfig()->getCallbackSuccessOrderRedirectPath());
    }

    /**
     * @return string
     */
    protected function getErrorMessageText()
    {
        $errorMessageText = 'Error:';
        $errorMessageText .= ' (' . $this->orderResponseHeaderTransfer->getDescription() . ' | ' . $this->orderResponseHeaderTransfer->getCode() . ')';

        return $errorMessageText;
    }

    /**
     * @param \SprykerEco\Yves\Computop\Converter\ConverterInterface $converter
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function getOrderResponseTransfer(ConverterInterface $converter)
    {
        $orderResponseTransfer = $converter->createResponseTransfer(
            $this->decryptedArray,
            $this->orderResponseHeaderTransfer
        );

        return $orderResponseTransfer;
    }
}
