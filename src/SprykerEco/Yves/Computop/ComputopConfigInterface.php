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
    public function getPaypalInitActionUrl();

    /**
     * @return string
     */
    public function getDirectDebitInitActionUrl();

    /**
     * @return string
     */
    public function getCreditCardInitActionUrl();

    /**
     * @return string
     */
    public function getPayNowInitActionUrl();
    
    /**
     * @return string
     */
    public function getEasyCreditInitActionUrl();

    /**
     * @return bool
     */
    public function getCreditCardTemplateEnabled();

    /**
     * @return string[]
     */
    public function getPaymentMethodsCaptureTypes();

    /**
     * @return string
     */
    public function getCreditCardTxType();

    /**
     * @return string
     */
    public function getPayNowTxType();

    /**
     * @return string
     */
    public function getPayPalTxType();
}
