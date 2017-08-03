<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Controller;

use Generated\Shared\Transfer\ComputopCreditCardOrderResponseTransfer;
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
            $quote->getPayment()->getComputopCreditCard()->setCreditCardOrderResponse(
                $this->getComputopCreditCardOrderResponseTransfer()
            );

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
        $responseTransfer = $this->getComputopCreditCardOrderResponseTransfer();

        $errorMessageText = self::ERROR_MESSAGE;
        $errorMessageText .= ' (' . $responseTransfer->getDescription() . ' | ' . $responseTransfer->getCode() . ')';

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
            ->createComputopService()
            ->getDecryptedArray($responseArray, Config::get(ComputopConstants::COMPUTOP_BLOWFISH_PASSWORD));

         return $this->createComputopCreditCardOrderResponseTransfer($decryptedArray);
    }

    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardOrderResponseTransfer
     */
    protected function createComputopCreditCardOrderResponseTransfer($decryptedArray)
    {
        $computopCreditCardResponseTransfer = new ComputopCreditCardOrderResponseTransfer();

        $computopCreditCardResponseTransfer->setMid($decryptedArray[ComputopConstants::MID_F_N]);
        $computopCreditCardResponseTransfer->setPayId($decryptedArray[ComputopConstants::PAY_ID_F_N]);
        $computopCreditCardResponseTransfer->setStatus($decryptedArray[ComputopConstants::STATUS_F_N]);
        $computopCreditCardResponseTransfer->setDescription($decryptedArray[ComputopConstants::DESCRIPTION_F_N]);
        $computopCreditCardResponseTransfer->setCode($decryptedArray[ComputopConstants::CODE_F_N]);
        $computopCreditCardResponseTransfer->setXid($decryptedArray[ComputopConstants::XID_F_N]);
        $computopCreditCardResponseTransfer->setTransId($decryptedArray[ComputopConstants::TRANS_ID_F_N]);
        $computopCreditCardResponseTransfer->setType($decryptedArray[ComputopConstants::TYPE_F_N]);
        $computopCreditCardResponseTransfer->setMac($decryptedArray[ComputopConstants::MAC_F_N]);
        $computopCreditCardResponseTransfer->setPcnr(isset($decryptedArray[ComputopConstants::PCN_R_F_N]) ? $decryptedArray[ComputopConstants::PCN_R_F_N] : null);
        $computopCreditCardResponseTransfer->setCCExpiry(isset($decryptedArray[ComputopConstants::CC_EXPIRY_F_N]) ? $decryptedArray[ComputopConstants::CC_EXPIRY_F_N] : null);
        $computopCreditCardResponseTransfer->setCCBrand(isset($decryptedArray[ComputopConstants::CC_BRAND_F_N]) ? $decryptedArray[ComputopConstants::CC_BRAND_F_N] : null);

        $computopCreditCardResponseTransfer->setHeader(
            $this->getFactory()->createComputopService()->extractHeader($decryptedArray)
        );

        return $computopCreditCardResponseTransfer;
    }

}
