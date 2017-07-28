<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Controller;

use Generated\Shared\Transfer\ComputopCreditCardResponseTransfer;
use Pyz\Yves\Checkout\Plugin\Provider\CheckoutControllerProvider;
use Spryker\Shared\Config\Config;
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
            $quote->getPayment()->getComputopCreditCard()->setCreditCardResponse($this->getResponseTransfer());

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
        $responseTransfer = $this->getResponseTransfer();

        $errorMessageText = self::ERROR_MESSAGE;
        $errorMessageText .= ' (' . $responseTransfer->getDescription() . ' | ' . $responseTransfer->getCode() . ')';

        return $errorMessageText;
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopCreditCardResponseTransfer
     */
    protected function getResponseTransfer()
    {
        $responseTransfer = new ComputopCreditCardResponseTransfer();
        $responseArray = $this->getApplication()['request']->query->all();

        if (isset($responseArray['Data'])) {
            $responseArray = $this->getDecryptedArray($responseArray);

            $responseTransfer->setLen($responseArray['Len']);
            $responseTransfer->setData($responseArray['Data']);
        }

        $responseTransfer->setMid($responseArray['mid']);
        $responseTransfer->setPayId($responseArray['PayID']);

        $responseTransfer->setTransId($responseArray['TransID']);
        $responseTransfer->setType(isset($responseArray['Type']) ? $responseArray['Type'] : '');
        $responseTransfer->setStatus($responseArray['Status']);
        $responseTransfer->setCode($responseArray['Code']);
        $responseTransfer->setMac($responseArray['MAC']);
        $responseTransfer->setXid(isset($responseArray['XID']) ? $responseArray['XID'] : '');
        $responseTransfer->setPcnr(isset($responseArray['PCNr']) ? $responseArray['PCNr'] : '');
        $responseTransfer->setCCExpiry(isset($responseArray['CCExpiry']) ? $responseArray['CCExpiry'] : '');
        $responseTransfer->setCCBrand(isset($responseArray['CCBrand']) ? $responseArray['CCBrand'] : '');

        $responseTransfer->setDescription(isset($responseArray['Description']) ? $responseArray['Description'] : '');

        return $responseTransfer;
    }

    /**
     * @param array $responseArray
     *
     * @return array
     */
    protected function getDecryptedArray($responseArray)
    {
        $responseDecryptedString = $this->getFactory()->createComputopService()->blowfishDecryptedValue(
            $responseArray['Data'],
            $responseArray['Len'],
            Config::get(ComputopConstants::COMPUTOP_BLOWFISH_PASSWORD)
        );
        $responseDecrypted = explode('&', $responseDecryptedString);
        foreach ($responseDecrypted as $value) {
            $data = explode('=', $value);
            $responseArray[array_shift($data)] = array_shift($data);
        }

        return $responseArray;
    }

}
