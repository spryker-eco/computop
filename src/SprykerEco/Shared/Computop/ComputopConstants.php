<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Computop;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ComputopConstants
{
    /**
     * Specification:
     *  - Computop merchant identifier.
     *
     * @api
     */
    public const PAYDIREKT_SHOP_KEY = 'COMPUTOP:PAYDIREKT_SHOP_KEY';

    /**
     * Specification:
     *  - Init API call endpoint for PayNow payment method.
     *
     * @api
     */
    public const PAY_NOW_INIT_ACTION = 'COMPUTOP:PAY_NOW_INIT_ACTION';

    /**
     * Specification:
     *  - Init API call endpoint for Credit Card payment method.
     *
     * @api
     */
    public const CREDIT_CARD_INIT_ACTION = 'COMPUTOP:CREDIT_CARD_INIT_ACTION';

    /**
     * Specification:
     *  - Init API call endpoint for PayPal payment method.
     *
     * @api
     */
    public const PAYPAL_INIT_ACTION = 'COMPUTOP:PAYPAL_INIT_ACTION';

    /**
     * Specification:
     *  - Init API call endpoint for Direct Debit payment method.
     *
     * @api
     */
    public const DIRECT_DEBIT_INIT_ACTION = 'COMPUTOP:DIRECT_DEBIT_INIT_ACTION';

    /**
     * Specification:
     *  - Init API call endpoint for Sofort payment method.
     *
     * @api
     */
    public const SOFORT_INIT_ACTION = 'COMPUTOP:SOFORT_INIT_ACTION';

    /**
     * Specification:
     *  - Init API call endpoint for Paydirect payment method.
     *
     * @api
     */
    public const PAYDIREKT_INIT_ACTION = 'COMPUTOP:PAYDIREKT_INIT_ACTION';

    /**
     * Specification:
     *  - Init API call endpoint for Ideal payment method.
     *
     * @api
     */
    public const IDEAL_INIT_ACTION = 'COMPUTOP:IDEAL_INIT_ACTION';

    /**
     * Specification:
     *  - Init API call endpoint for Easy Credit payment method.
     *
     * @api
     */
    public const EASY_CREDIT_INIT_ACTION = 'COMPUTOP:EASY_CREDIT_INIT_ACTION';

    /**
     * Specification:
     *  - Status API call endpoint for Easy Credit payment method.
     *
     * @api
     */
    public const EASY_CREDIT_STATUS_ACTION = 'COMPUTOP:EASY_CREDIT_STATUS_ACTION';

    /**
     * Specification:
     *  - Authorize API call endpoint for Easy Credit payment method.
     *
     * @api
     */
    public const EASY_CREDIT_AUTHORIZE_ACTION = 'COMPUTOP:EASY_CREDIT_AUTHORIZE_ACTION';

    /**
     * Specification:
     *  - Authorize API call endpoint.
     *
     * @api
     */
    public const AUTHORIZE_ACTION = 'COMPUTOP:AUTHORIZE_ACTION';

    /**
     * Specification:
     *  - Capture API call endpoint.
     *
     * @api
     */
    public const CAPTURE_ACTION = 'COMPUTOP:CAPTURE_ACTION';

    /**
     * Specification:
     *  - Reserve API call endpoint.
     *
     * @api
     */
    public const REVERSE_ACTION = 'COMPUTOP:REVERSE_ACTION';

    /**
     * Specification:
     *  - Inquire API call endpoint.
     *
     * @api
     */
    public const INQUIRE_ACTION = 'COMPUTOP:INQUIRE_ACTION';

    /**
     * Specification:
     *  - Refund API call endpoint.
     *
     * @api
     */
    public const REFUND_ACTION = 'COMPUTOP:REFUND_ACTION';

    /**
     * Specification:
     *  -
     *
     * @api
     */
    public const RESPONSE_MAC_REQUIRED = 'COMPUTOP:RESPONSE_MAC_REQUIRED';

    /**
     * Specification:
     *  - MAC is required for methods (to check MAC on response).
     *
     * @api
     */
    public const PAYMENT_METHODS_WITHOUT_ORDER_CALL = 'COMPUTOP:PAYMENT_METHODS_WITHOUT_ORDER_CALL';

    /**
     * Specification:
     *  - Is custom template enabled for Credit Card payment method.
     *
     * @api
     */
    public const CREDIT_CARD_TEMPLATE_ENABLED = 'COMPUTOP:CREDIT_CARD_TEMPLATE_ENABLED';

    /**
     * Specification:
     *  - Is CRIF risk check enabled.
     *
     * @api
     */
    public const CRIF_ENABLED = 'COMPUTOP:CRIF_ENABLED';

    /**
     * Specification:
     *  - List of payment methods available with green response code.
     *
     * @api
     */
    public const CRIF_GREEN_AVAILABLE_PAYMENT_METHODS = 'COMPUTOP:CRIF_GREEN_AVAILABLE_PAYMENT_METHODS';

    /**
     * Specification:
     *  - List of payment methods available with yellow response code.
     *
     * @api
     */
    public const CRIF_YELLOW_AVAILABLE_PAYMENT_METHODS = 'COMPUTOP:CRIF_YELLOW_AVAILABLE_PAYMENT_METHODS';

    /**
     * Specification:
     *  - List of payment methods available with red response code.
     *
     * @api
     */
    public const CRIF_RED_AVAILABLE_PAYMENT_METHODS = 'COMPUTOP:CRIF_RED_AVAILABLE_PAYMENT_METHODS';

    /**
     * Specification:
     *  - TX TYPE for Credit Card payment method (empty string).
     *
     * @api
     */
    public const CREDIT_CARD_TX_TYPE = 'COMPUTOP:CREDIT_CARD_TX_TYPE';

    /**
     * Specification:
     *  - TX TYPE for PayNow payment method (empty string).
     *
     * @api
     */
    public const PAY_NOW_TX_TYPE = 'COMPUTOP:PAY_NOW_TX_TYPE';

    /**
     * Specification:
     *  - TX TYPE for PayPal payment method (Auth).
     *
     * @api
     */
    public const PAY_PAL_TX_TYPE = 'COMPUTOP:PAY_PAL_TX_TYPE';

    /**
     * Specification:
     *  - Issuer ID for Ideal payment method.
     *
     * @api
     */
    public const IDEAL_ISSUER_ID = 'COMPUTOP:IDEAL_ISSUER_ID';
}
