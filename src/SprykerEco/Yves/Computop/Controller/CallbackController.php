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
use SprykerEco\Yves\Computop\Converter\ConverterInterface;

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
            ->getDecryptedArray($responseArray, Config::get(ComputopConstants::COMPUTOP_BLOWFISH_PASSWORD));

        $this->orderResponseHeaderTransfer = $this->getFactory()->getComputopService()->extractHeader(
            $this->decryptedArray,
            ComputopConstants::ORDER_METHOD
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

        return $this->successAction();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successPayPalAction()
    {
        $this->orderResponseTransfer = $this->getOrderResponseTransfer(
            $this->getFactory()->createOrderPayPalConverter()
        );

        return $this->successAction();
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function successAction()
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $quoteTransfer = $this->getFactory()->createPaymentHandler()->addPaymentToQuote(
            $quoteTransfer,
            $this->orderResponseTransfer
        );

        if (!$quoteTransfer->getCustomer()) {
            $this->addErrorMessage('Please login and try again');
        }

        $this->getFactory()->getQuoteClient()->setQuote($quoteTransfer);

        return $this->redirectResponseInternal(CheckoutControllerProvider::CHECKOUT_SUMMARY);
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
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
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
