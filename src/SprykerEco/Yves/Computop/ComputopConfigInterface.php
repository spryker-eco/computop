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
    public function getEasyCreditSuccessAction(): string;

    /**
     * @return string
     */
    public function getCallbackSuccessOrderRedirectPath(): string;

    /**
     * @return string
     */
    public function getCallbackSuccessCaptureRedirectPath(): string;

    /**
     * @return string
     */
    public function getCallbackFailureRedirectPath(): string;

    /**
     * @return array
     */
    public function getPaymentMethodsWithoutOrderCall(): array;

    /**
     * @return string
     */
    public function getBaseUrlSsl(): string;

    /**
     * @return string
     */
    public function getMerchantId(): string;

    /**
     * @return string
     */
    public function getPaydirektShopKey(): string;

    /**
     * @return string
     */
    public function getBlowfishPassword(): string;

    /**
     * @return string
     */
    public function getPaypalInitActionUrl(): string;

    /**
     * @return string
     */
    public function getDirectDebitInitActionUrl(): string;

    /**
     * @return string
     */
    public function getCreditCardInitActionUrl(): string;

    /**
     * @return string
     */
    public function getPayNowInitActionUrl(): string;

    /**
     * @return string
     */
    public function getEasyCreditInitActionUrl(): string;

    /**
     * @return bool
     */
    public function getCreditCardTemplateEnabled(): bool;

    /**
     * @return string[]
     */
    public function getPaymentMethodsCaptureTypes(): array;

    /**
     * @return string
     */
    public function getCreditCardTxType(): string;

    /**
     * @return string
     */
    public function getPayNowTxType(): string;

    /**
     * @return string
     */
    public function getPayPalTxType(): string;

    /**
     * @return string
     */
    public function getEtiId(): string;

    /**
     * Specification:
     * - Max items for `orderDesc(n)` field for PayPal payment page.
     *
     * @api
     *
     * @return int
     */
    public function getMaxOrderDescriptionItemsForPayPalPaymentPage(): int;
}
