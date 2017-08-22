<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Controller;

use Pyz\Yves\Checkout\Plugin\Provider\CheckoutControllerProvider;
use Spryker\Shared\Config\Config;
use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerEco\Shared\Computop\ComputopConstants;

/**
 * @method \SprykerEco\Yves\Computop\ComputopFactory getFactory()
 */
class CallbackController extends AbstractController
{

    /**
     * @var \Generated\Shared\Transfer\ComputopCreditCardOrderResponseTransfer
     */
    protected $creditCardOrderResponseTransfer;

    /**
     * @return void
     */
    public function initialize()
    {
        $this->creditCardOrderResponseTransfer = $this->getComputopCreditCardOrderResponseTransfer();
        $this->getFactory()->getComputopClient()->logResponse($this->creditCardOrderResponseTransfer->getHeader());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successAction()
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $quoteTransfer = $this->getFactory()->createPaymentHandler()->addPaymentToQuote(
            $quoteTransfer,
            $this->creditCardOrderResponseTransfer
        );

        if (!$quoteTransfer->getCustomer()) {
            $this->addErrorMessage('Please login and try again');
        }

        $this->getFactory()->getQuoteClient()->setQuote($quoteTransfer);

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
        $responseTransfer = $this->creditCardOrderResponseTransfer;
        $errorMessageText = 'Error:';
        $errorMessageText .= ' (' . $responseTransfer->getHeader()->getDescription() . ' | ' . $responseTransfer->getHeader()->getCode() . ')';

        return $errorMessageText;
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopCreditCardOrderResponseTransfer
     */
    protected function getComputopCreditCardOrderResponseTransfer()
    {
        $responseArray = $this->getApplication()['request']->query->all();
        $decryptedArray = $this
            ->getFactory()
            ->getComputopService()
            ->getDecryptedArray($responseArray, Config::get(ComputopConstants::COMPUTOP_BLOWFISH_PASSWORD));

        $header = $this->getFactory()->getComputopService()->extractHeader($decryptedArray, ComputopConstants::ORDER_METHOD);
        $computopCreditCardResponseTransfer = $this->getFactory()->createOrderCreditCardConverter()->createResponseTransfer($decryptedArray, $header);

        return $computopCreditCardResponseTransfer;
    }

}
