<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\Computop\ComputopConfig as SharedComputopConfig;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Shared\ComputopApi\ComputopApiConstants;

class ComputopConfig extends AbstractBundleConfig implements ComputopConfigInterface
{
    public const ETI_ID = 'Spryker – MV:%s'; //Parameter is requested by Computop, Module version
    public const FINISH_AUTH = 'Y'; //Only with ETM: Transmit value <Y> in order to stop the renewal of guaranteed authorizations and rest amounts after partial captures.
    public const RESPONSE_ENCRYPT_TYPE = 'encrypt';

    protected const EASY_CREDIT_SUCCESS_ACTION = 'checkout-summary';

    /**
     * @return string
     */
    public function getEasyCreditSuccessAction(): string
    {
        return static::EASY_CREDIT_SUCCESS_ACTION;
    }

    /**
     * @return string
     */
    public function getCallbackSuccessOrderRedirectPath(): string
    {
        return 'checkout-summary';
    }

    /**
     * @return string
     */
    public function getCallbackSuccessCaptureRedirectPath(): string
    {
        return 'checkout-success';
    }

    /**
     * @return string
     */
    public function getCallbackFailureRedirectPath(): string
    {
        return 'checkout-payment';
    }

    /**
     * @return string[]
     */
    public function getPaymentMethodsWithoutOrderCall(): array
    {
        return $this->get(ComputopConstants::PAYMENT_METHODS_WITHOUT_ORDER_CALL);
    }

    /**
     * @return string
     */
    public function getBaseUrlSsl(): string
    {
        return $this->get(ApplicationConstants::BASE_URL_SSL_YVES);
    }

    /**
     * @return string
     */
    public function getMerchantId(): string
    {
        return $this->get(ComputopApiConstants::MERCHANT_ID);
    }

    /**
     * @return string
     */
    public function getPaydirektShopKey(): string
    {
        return $this->get(ComputopConstants::PAYDIREKT_SHOP_KEY);
    }

    /**
     * @return string
     */
    public function getBlowfishPassword(): string
    {
        return $this->get(ComputopApiConstants::BLOWFISH_PASSWORD);
    }

    /**
     * @return string
     */
    public function getPaypalInitActionUrl(): string
    {
        return $this->get(ComputopConstants::PAYPAL_INIT_ACTION);
    }

    /**
     * @return string
     */
    public function getDirectDebitInitActionUrl(): string
    {
        return $this->get(ComputopConstants::DIRECT_DEBIT_INIT_ACTION);
    }

    /**
     * @return string
     */
    public function getCreditCardInitActionUrl(): string
    {
        return $this->get(ComputopConstants::CREDIT_CARD_INIT_ACTION);
    }

    /**
     * @return string
     */
    public function getPayNowInitActionUrl(): string
    {
        return $this->get(ComputopConstants::PAY_NOW_INIT_ACTION);
    }

    /**
     * @return string
     */
    public function getEasyCreditInitActionUrl(): string
    {
        return $this->get(ComputopConstants::EASY_CREDIT_INIT_ACTION);
    }

    /**
     * @return bool
     */
    public function getCreditCardTemplateEnabled(): bool
    {
        return $this->get(ComputopConstants::CREDIT_CARD_TEMPLATE_ENABLED);
    }

    /**
     * @return string[]
     */
    public function getPaymentMethodsCaptureTypes(): array
    {
        return $this->get(ComputopApiConstants::PAYMENT_METHODS_CAPTURE_TYPES);
    }

    /**
     * @return string
     */
    public function getCreditCardTxType(): string
    {
        return $this->get(ComputopConstants::CREDIT_CARD_TX_TYPE);
    }

    /**
     * @return string
     */
    public function getPayNowTxType(): string
    {
        return $this->get(ComputopConstants::PAY_NOW_TX_TYPE);
    }

    /**
     * @return string
     */
    public function getPayPalTxType(): string
    {
        return $this->get(ComputopConstants::PAY_PAL_TX_TYPE);
    }

    /**
     * @return string
     */
    public function getEtiId(): string
    {
        return sprintf(static::ETI_ID, SharedComputopConfig::COMPUTOP_MODULE_VERSION);
    }
}
