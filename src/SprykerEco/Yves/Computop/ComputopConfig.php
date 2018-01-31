<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\Computop\ComputopConstants;

class ComputopConfig extends AbstractBundleConfig implements ComputopConfigInterface
{
    const ETI_ID = '0.0.1'; //Parameter is requested by Computop
    const FINISH_AUTH = 'Y'; //Only with ETM: Transmit value <Y> in order to stop the renewal of guaranteed authorizations and rest amounts after partial captures.
    const RESPONSE_ENCRYPT_TYPE = 'encrypt';
    const TX_TYPE_ORDER = 'Order';

    /**
     * @return string
     */
    public function getCallbackSuccessOrderRedirectPath()
    {
        return 'checkout-summary';
    }

    /**
     * @return string
     */
    public function getCallbackSuccessCaptureRedirectPath()
    {
        return 'checkout-success';
    }

    /**
     * @return string
     */
    public function getCallbackFailureRedirectPath()
    {
        return 'checkout-payment';
    }

    /**
     * @return array
     */
    public function getPaymentMethodsWithoutOrderCall()
    {
        return $this->get(ComputopConstants::PAYMENT_METHODS_WITHOUT_ORDER_CALL);
    }

    /**
     * @return string
     */
    public function getBaseUrlSsl()
    {
        return $this->get(ApplicationConstants::BASE_URL_SSL_YVES);
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->get(ComputopConstants::MERCHANT_ID);
    }

    /**
     * @return string
     */
    public function getPaydirektShopKey()
    {
        return $this->get(ComputopConstants::PAYDIREKT_SHOP_KEY);
    }

    /**
     * @return string
     */
    public function getBlowfishPassword()
    {
        return $this->get(ComputopConstants::BLOWFISH_PASSWORD);
    }

    /**
     * @return string
     */
    public function getPaypalInitAction()
    {
        return $this->get(ComputopConstants::PAYPAL_INIT_ACTION);
    }

    /**
     * @return string
     */
    public function getDirectDebitInitAction()
    {
        return $this->get(ComputopConstants::DIRECT_DEBIT_INIT_ACTION);
    }

    /**
     * @return string
     */
    public function getCreditCardInitAction()
    {
        return $this->get(ComputopConstants::CREDIT_CARD_INIT_ACTION);
    }
    
    /**
     * @return string
     */
    public function getEasyCreditInitAction()
    {
        return $this->get(ComputopConstants::EASY_CREDIT_INIT_ACTION);
    }

    /**
     * @return string
     */
    public function getCreditCardTemplateEnabled()
    {
        return $this->get(ComputopConstants::CREDIT_CARD_TEMPLATE_ENABLED);
    }
}
