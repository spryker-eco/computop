<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop;

interface ComputopConfigInterface
{
    /**
     * @return string
     */
    public function getCallbackSuccessOrderRedirectPath();

    /**
     * @return string
     */
    public function getCallbackSuccessCaptureRedirectPath();

    /**
     * @return string
     */
    public function getCallbackFailureRedirectPath();

    /**
     * @return array
     */
    public function getPaymentMethodsWithoutOrderCall();

    /**
     * @return string
     */
    public function getBaseUrlSsl();

    /**
     * @return string
     */
    public function getMerchantId();

    /**
     * @return string
     */
    public function getPaydirektShopKey();

    /**
     * @return string
     */
    public function getBlowfishPassword();

    /**
     * @return string
     */
    public function getPaypalInitAction();

    /**
     * @return string
     */
    public function getDirectDebitInitAction();

    /**
     * @return string
     */
    public function getCreditCardInitAction();
    
    /**
     * @return string
     */
    public function getEasyCreditInitAction();

    /**
     * @return string
     */
    public function getCreditCardTemplateEnabled();
}
