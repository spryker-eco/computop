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

class CallbackController extends AbstractController
{

    const ERROR_MESSAGE = 'Error message';
    const SUCCESS_MESSAGE = 'Success message';

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successAction()
    {
        //Add data to quote + redirect
        $this->addSuccessMessage(self::SUCCESS_MESSAGE);

        return $this->redirectResponseInternal(CheckoutControllerProvider::CHECKOUT_SUMMARY);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function failureAction()
    {
        $responseDecryptedArray = $this->getResponseDecryptedArray();

        $errorMessageText = self::ERROR_MESSAGE;

        //Add error message + redirect
        if ($responseDecryptedArray['Description'] && $responseDecryptedArray['Code']) {
            $errorMessageText .= ' (' . $responseDecryptedArray['Description'] . ' | ' . $responseDecryptedArray['Code'] . ')';
        }

        $this->addErrorMessage($errorMessageText);

        return $this->redirectResponseInternal(CheckoutControllerProvider::CHECKOUT_PAYMENT);
    }

    /**
     * @return array
     */
    protected function getResponseDecryptedArray()
    {
        $responseArray = $this->getApplication()['request']->query->all();

        if ($responseArray['Data'] !== null) {
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
        }

        return $responseArray;
    }

}
