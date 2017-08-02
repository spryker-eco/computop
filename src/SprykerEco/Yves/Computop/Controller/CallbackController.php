<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Controller;

use Pyz\Yves\Checkout\Plugin\Provider\CheckoutControllerProvider;
use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerEco\Shared\Computop\ComputopConstants;

class CallbackController extends AbstractController
{

    const ERROR_MESSAGE = 'Error message';
    const SUCCESS_MESSAGE = 'Success message';

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successAction()
    {
        $quote = $this->getFactory()->createQuoteClient()->getQuote();

        if ($quote->getPayment() !== null) {
            $quote->getPayment()->getComputopCreditCard()->setCreditCardResponse($this->getCreditCardResponseTransfer());

            $quote->getPayment()->setPaymentProvider(ComputopConstants::PROVIDER_NAME);
            $quote->getPayment()->setPaymentMethod(ComputopConstants::PAYMENT_METHOD_CREDIT_CARD);
            $quote->getPayment()->setPaymentSelection(ComputopConstants::PAYMENT_METHOD_CREDIT_CARD);

            $this->getFactory()->createQuoteClient()->setQuote($quote);
        }

        return $this->redirectResponseInternal(CheckoutControllerProvider::CHECKOUT_SUMMARY);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function failureAction()
    {
        $this->addErrorMessage($this->getErrorMessageText());

        return $this->redirectResponseInternal(CheckoutControllerProvider::CHECKOUT_PAYMENT);
    }

    /**
     * @return string
     */
    protected function getErrorMessageText()
    {
        $responseTransfer = $this->getCreditCardResponseTransfer();

        $errorMessageText = self::ERROR_MESSAGE;
        $errorMessageText .= ' (' . $responseTransfer->getDescription() . ' | ' . $responseTransfer->getCode() . ')';

        return $errorMessageText;
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopCreditCardResponseTransfer
     */
    protected function getCreditCardResponseTransfer()
    {
        $responseArray = $this->getApplication()['request']->query->all();

         return $this->getFactory()->createComputopService()->getComputopResponseTransfer($responseArray);
    }

}
