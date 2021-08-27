<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Controller;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopNotificationTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Shared\ComputopApi\ComputopApiConstants;
use SprykerEco\Yves\Computop\Handler\ComputopPrePostPaymentHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerEco\Yves\Computop\ComputopFactory getFactory()
 * @method \SprykerEco\Client\Computop\ComputopClientInterface getClient()
 */
class CallbackController extends AbstractController
{
    public const MESSAGE_PAYMENT_SUCCESS = 'Your order has been placed successfully! Thank you for shopping with us!';

    public const MESSAGE_LOG_OUT_ERROR = 'Please login and try again.';

    public const MESSAGE_RESPONSE_ERROR = 'Error: %s ( %s )';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successCreditCardAction(Request $request)
    {
        return $this->executeSuccessPostPlaceAction(
            $this->getFactory()->createCreditCardPaymentHandler(),
            $request->request->all()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successPayNowAction(Request $request)
    {
        return $this->executeSuccessPostPlaceAction(
            $this->getFactory()->createPayNowPaymentHandler(),
            $request->request->all()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successPayPalAction(Request $request)
    {
        return $this->executeSuccessPostPlaceAction(
            $this->getFactory()->createPayPalPaymentHandler(),
            $request->query->all()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successDirectDebitAction(Request $request)
    {
        return $this->executeSuccessPostPlaceAction(
            $this->getFactory()->createDirectDebitPaymentHandler(),
            $request->query->all()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successEasyCreditAction(Request $request)
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $quoteTransfer = $this->getFactory()
            ->createEasyCreditPaymentHandler()
            ->handle($quoteTransfer, $request->query->all());

        $this->getFactory()->getQuoteClient()->setQuote($quoteTransfer);
        $statusResponse = $quoteTransfer->getPayment()->getComputopEasyCredit()->getEasyCreditStatusResponse();

        if (!$statusResponse->getHeader()->getIsSuccess()) {
            $this->addErrorMessage($statusResponse->getErrorText());

            return $this->redirectResponseInternal($this->getFactory()->getComputopConfig()->getCallbackFailureRedirectPath());
        }

        return $this->redirectResponseInternal($this->getFactory()->getComputopConfig()->getEasyCreditSuccessAction());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successPayuCeeSingleAction(Request $request): RedirectResponse
    {
        return $this->executeSuccessPostPlaceAction(
            $this->getFactory()->createPayuCeeSinglePaymentHandler(),
            $request->query->all()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successPaydirektAction(Request $request)
    {
        return $this->executeSuccessPostPlaceAction(
            $this->getFactory()->createPaydirektPaymentHandler(),
            $request->query->all()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successSofortAction(Request $request)
    {
        return $this->executeSuccessPostPlaceAction(
            $this->getFactory()->createSofortPaymentHandler(),
            $request->query->all()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successIdealAction(Request $request)
    {
        return $this->executeSuccessPostPlaceAction(
            $this->getFactory()->createIdealPaymentHandler(),
            $request->query->all()
        );
    }

    /**
     * @param \SprykerEco\Yves\Computop\Handler\ComputopPrePostPaymentHandlerInterface $handler
     * @param array $responseArray
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeSuccessPostPlaceAction(ComputopPrePostPaymentHandlerInterface $handler, array $responseArray)
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $quoteTransfer = $handler->handle($quoteTransfer, $responseArray);

        if (!$quoteTransfer->getCustomer()) {
            $this->addSuccessMessage(static::MESSAGE_PAYMENT_SUCCESS);
        }

        return $this->redirectResponseInternal(
            $this->getFactory()->getComputopConfig()->getCallbackSuccessCaptureRedirectPath()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \SprykerEco\Service\ComputopApi\Exception\ComputopApiConverterException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function failureAction(Request $request)
    {
        $requestParameters = $request->query->all();

        if ($request->isMethod(Request::METHOD_POST)) {
            $requestParameters = $request->request->all();
        }

        $computopApiService = $this->getFactory()->getComputopApiService();

        $decryptedArray = $computopApiService
            ->decryptResponseHeader($requestParameters, Config::get(ComputopApiConstants::BLOWFISH_PASSWORD));

        $responseHeaderTransfer = $computopApiService
            ->extractResponseHeader($decryptedArray, ComputopConfig::INIT_METHOD);

        $this->addErrorMessage($this->getErrorMessageText($responseHeaderTransfer));

        $this->resetQuoteAfterFail();

        return $this->redirectResponseInternal($this->getFactory()->getComputopConfig()->getCallbackFailureRedirectPath());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function notifyAction(Request $request)
    {
        $decryptedArray = $this->getFactory()
            ->getComputopApiService()
            ->decryptResponseHeader($request->request->all(), Config::get(ComputopApiConstants::BLOWFISH_PASSWORD));

        $responseHeaderTransfer = $this->getFactory()
            ->getComputopApiService()
            ->extractResponseHeader($decryptedArray, '');

        $computopNotificationTransfer = (new ComputopNotificationTransfer())
            ->fromArray($responseHeaderTransfer->toArray(), true)
            ->setType($decryptedArray[ComputopApiConfig::NOTIFICATION_PARAMETER_PAYMENT_TYPE] ?? '');

        $computopNotificationTransfer = $this->getClient()->processNotification($computopNotificationTransfer);

        if ($computopNotificationTransfer->getIsProcessed()) {
            return new Response();
        }

        return new Response('', Response::HTTP_NOT_ACCEPTABLE);
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

    /**
     * @return void
     */
    protected function resetQuoteAfterFail(): void
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $quoteTransfer->setIsOrderPlacedSuccessfully(null);
        $quoteTransfer->setOrderReference(null);
        $quoteTransfer->setPayment();
        $this->getFactory()->getQuoteClient()->setQuote($quoteTransfer);
    }
}
