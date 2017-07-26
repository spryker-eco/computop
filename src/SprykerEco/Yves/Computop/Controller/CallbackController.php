<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Controller;

use Pyz\Yves\Checkout\Plugin\Provider\CheckoutControllerProvider;
use Spryker\Yves\Kernel\Controller\AbstractController;

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
        $responcseArray = $this->getApplication()['request']->query->all();

        $errorMessageText = self::ERROR_MESSAGE;

        //Add error message + redirect
        if ($responcseArray['Description'] && $responcseArray['Code']) {
            $errorMessageText = $responcseArray['Description'] . ' (Code - ' . $responcseArray['Code'] . ')';
        }

        $this->addErrorMessage($errorMessageText);

        return $this->redirectResponseInternal(CheckoutControllerProvider::CHECKOUT_PAYMENT);
    }

}
