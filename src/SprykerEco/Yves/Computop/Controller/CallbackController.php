<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Controller;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Config\Config;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\ComputopConstants;
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
        return $this->successPostPlaceAction($this->getFactory()->createEasyCreditPaymentHandler());
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
        $quoteTransfer = $handler
            ->handle(
                $quoteTransfer,
                $this->responseArray
            );

        if (!$this->validateEaseCreditStatusResponse($quoteTransfer)) {
            return $this->redirectResponseInternal($this->getFactory()->getComputopConfig()->getCallbackFailureRedirectPath());
        }

        if (!$quoteTransfer->getCustomer()) {
            $this->addSuccessMessage(static::MESSAGE_PAYMENT_SUCCESS);
        }

        return $this->redirectResponseInternal($this->getFactory()->getComputopConfig()->getCallbackSuccessCaptureRedirectPath());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function failureAction()
    {
        $decryptedArray = $this
            ->getFactory()
            ->getComputopService()
            ->getDecryptedArray($this->responseArray, Config::get(ComputopConstants::BLOWFISH_PASSWORD));

        $responseHeaderTransfer = $this->getFactory()->getComputopService()->extractHeader(
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
            ->getComputopService()
            ->getDecryptedArray($this->responseArray, Config::get(ComputopConstants::BLOWFISH_PASSWORD));

        $responseHeaderTransfer = $this->getFactory()->getComputopService()->extractHeader(
            $decryptedArray,
            ComputopConfig::INIT_METHOD
        );
        $this->addErrorMessage($this->getErrorMessageText($responseHeaderTransfer));

        return $this->redirectResponseInternal($this->getFactory()->getComputopConfig()->getCallbackFailureRedirectPath());
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $responseHeaderTransfer
     *
     * @return string
     */
    protected function getErrorMessageText(ComputopResponseHeaderTransfer $responseHeaderTransfer)
    {
        $errorText = $responseHeaderTransfer->getDescription();
        $errorCode = $responseHeaderTransfer->getCode();
        $errorMessageText = sprintf(static::MESSAGE_RESPONSE_ERROR, $errorText, $errorCode);

        return $errorMessageText;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function validateEaseCreditStatusResponse(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getPayment()->getPaymentMethod() === ComputopConfig::PAYMENT_METHOD_EASY_CREDIT
            && !$quoteTransfer->getPayment()->getComputopEasyCredit()->getEasyCreditStatusResponse()->getHeader()->getIsSuccess()
        ) {
            $this->addErrorMessage(
                $quoteTransfer->getPayment()->getComputopEasyCredit()->getEasyCreditStatusResponse()->getErrorText()
            );

            return false;
        }

        return true;
    }
}
