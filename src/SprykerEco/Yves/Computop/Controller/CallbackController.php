<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Controller;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Config\Config;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\ComputopApi\ComputopApiConstants;
use SprykerEco\Yves\Computop\Handler\ComputopPrePostPaymentHandlerInterface;

/**
 * @method \SprykerEco\Yves\Computop\ComputopFactory getFactory()
 */
class CallbackController extends AbstractController
{
    const MESSAGE_PAYMENT_SUCCESS = 'Your order has been placed successfully! Thank you for shopping with us!';

    const MESSAGE_LOG_OUT_ERROR = 'Please login and try again.';

    const MESSAGE_RESPONSE_ERROR = 'Error: %s ( %s )';

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successCreditCardAction()
    {
        return $this->successPostPlaceAction($this->getFactory()->createCreditCardPaymentHandler());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successPayNowAction()
    {
        return $this->successPostPlaceAction($this->getFactory()->createPayNowPaymentHandler());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successPayPalAction()
    {
        return $this->successPostPlaceAction($this->getFactory()->createPayPalPaymentHandler());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successDirectDebitAction()
    {
        return $this->successPostPlaceAction($this->getFactory()->createDirectDebitPaymentHandler());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successEasyCreditAction()
    {
        return $this->processEasyCreditSuccessResponse($this->getFactory()->createEasyCreditPaymentHandler());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successPaydirektAction()
    {
        return $this->successPostPlaceAction($this->getFactory()->createPaydirektPaymentHandler());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successSofortAction()
    {
        return $this->successPostPlaceAction($this->getFactory()->createSofortPaymentHandler());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successIdealAction()
    {
        return $this->successPostPlaceAction($this->getFactory()->createIdealPaymentHandler());
    }

    /**
     * @param \SprykerEco\Yves\Computop\Handler\ComputopPrePostPaymentHandlerInterface $handler
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function successPostPlaceAction(ComputopPrePostPaymentHandlerInterface $handler)
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $quoteTransfer = $handler->handle($quoteTransfer, $this->responseArray);

        if (!$quoteTransfer->getCustomer()) {
            $this->addSuccessMessage(static::MESSAGE_PAYMENT_SUCCESS);
        }

        return $this->redirectResponseInternal($this->getFactory()->getComputopConfig()->getCallbackSuccessCaptureRedirectPath());
    }

    /**
     * @param \SprykerEco\Yves\Computop\Handler\ComputopPrePostPaymentHandlerInterface $handler
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function processEasyCreditSuccessResponse(ComputopPrePostPaymentHandlerInterface $handler)
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $quoteTransfer = $handler->handle($quoteTransfer, $this->responseArray);
        $this->getFactory()->getQuoteClient()->setQuote($quoteTransfer);
        $statusResponse = $quoteTransfer->getPayment()->getComputopEasyCredit()->getEasyCreditStatusResponse();

        if (!$statusResponse->getHeader()->getIsSuccess()) {
            $this->addErrorMessage($statusResponse->getErrorText());

            return $this->redirectResponseInternal($this->getFactory()->getComputopConfig()->getCallbackFailureRedirectPath());
        }

        return $this->redirectResponseInternal($this->getFactory()->getComputopConfig()->getEasyCreditSuccessAction());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function failureAction()
    {
        $decryptedArray = $this
            ->getFactory()
            ->getComputopApiService()
            ->decryptResponseHeader($this->responseArray, Config::get(ComputopApiConstants::BLOWFISH_PASSWORD));

        $responseHeaderTransfer = $this->getFactory()->getComputopApiService()->extractResponseHeader(
            $decryptedArray,
            ComputopConfig::INIT_METHOD
        );
        $this->addErrorMessage($this->getErrorMessageText($responseHeaderTransfer));

        return $this->redirectResponseInternal($this->getFactory()->getComputopConfig()->getCallbackFailureRedirectPath());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function notifyAction()
    {
        $decryptedArray = $this
            ->getFactory()
            ->getComputopApiService()
            ->decryptResponseHeader($this->responseArray, Config::get(ComputopApiConstants::BLOWFISH_PASSWORD));

        $responseHeaderTransfer = $this->getFactory()->getComputopApiService()->extractResponseHeader(
            $decryptedArray,
            ComputopConfig::INIT_METHOD
        );
        $this->addErrorMessage($this->getErrorMessageText($responseHeaderTransfer));

        return $this->redirectResponseInternal($this->getFactory()->getComputopConfig()->getCallbackFailureRedirectPath());
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $responseHeaderTransfer
     *
     * @return string
     */
    protected function getErrorMessageText(ComputopApiResponseHeaderTransfer $responseHeaderTransfer)
    {
        $errorText = $responseHeaderTransfer->getDescription();
        $errorCode = $responseHeaderTransfer->getCode();
        $errorMessageText = sprintf(static::MESSAGE_RESPONSE_ERROR, $errorText, $errorCode);

        return $errorMessageText;
    }
}
