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
    public const FINISH_AUTH = 'Y'; //Only with ETM: Transmit value <Y> in order to stop the renewal of guaranteed authorizations and rest amounts after partial captures.
    public const RESPONSE_ENCRYPT_TYPE = 'encrypt';

    protected const EASY_CREDIT_SUCCESS_ACTION = 'checkout-summary';

    /**
     * @api
     *
     * @return string
     */
    public function getEasyCreditSuccessAction(): string
    {
        return static::EASY_CREDIT_SUCCESS_ACTION;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCallbackSuccessOrderRedirectPath(): string
    {
        return 'checkout-summary';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCallbackSuccessCaptureRedirectPath(): string
    {
        return 'checkout-success';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCallbackFailureRedirectPath(): string
    {
        return 'checkout-payment';
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getPaymentMethodsWithoutOrderCall(): array
    {
        return $this->get(ComputopConstants::PAYMENT_METHODS_WITHOUT_ORDER_CALL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getBaseUrlSsl(): string
    {
        return $this->get(ApplicationConstants::BASE_URL_SSL_YVES);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getMerchantId(): string
    {
        return $this->get(ComputopApiConstants::MERCHANT_ID);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPaydirektShopKey(): string
    {
        return $this->get(ComputopConstants::PAYDIREKT_SHOP_KEY);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getBlowfishPassword(): string
    {
        return $this->get(ComputopApiConstants::BLOWFISH_PASSWORD);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPaypalInitActionUrl(): string
    {
        return $this->get(ComputopConstants::PAYPAL_INIT_ACTION);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDirectDebitInitActionUrl(): string
    {
        return $this->get(ComputopConstants::DIRECT_DEBIT_INIT_ACTION);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCreditCardInitActionUrl(): string
    {
        return $this->get(ComputopConstants::CREDIT_CARD_INIT_ACTION);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPayNowInitActionUrl(): string
    {
        return $this->get(ComputopConstants::PAY_NOW_INIT_ACTION);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getEasyCreditInitActionUrl(): string
    {
        return $this->get(ComputopConstants::EASY_CREDIT_INIT_ACTION);
    }

    /**
     * Specification:
     * - Get PayU CEE Single init action url
     *
     * @api
     *
     * @return string|null
     */
    public function getPayuCeeSingleInitAction(): ?string
    {
        return $this->get(ComputopConstants::PAYU_CEE_SINGLE_INIT_ACTION);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function getCreditCardTemplateEnabled(): bool
    {
        return $this->get(ComputopConstants::CREDIT_CARD_TEMPLATE_ENABLED);
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getPaymentMethodsCaptureTypes(): array
    {
        return $this->get(ComputopApiConstants::PAYMENT_METHODS_CAPTURE_TYPES);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCreditCardTxType(): string
    {
        return $this->get(ComputopConstants::CREDIT_CARD_TX_TYPE);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPayNowTxType(): string
    {
        return $this->get(ComputopConstants::PAY_NOW_TX_TYPE);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPayPalTxType(): string
    {
        return $this->get(ComputopConstants::PAY_PAL_TX_TYPE);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getEtiId(): string
    {
        return SharedComputopConfig::COMPUTOP_MODULE_VERSION;
    }
}
