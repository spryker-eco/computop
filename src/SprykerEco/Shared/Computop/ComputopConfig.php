<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Computop;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ComputopConfig extends AbstractSharedConfig
{
    /**
     * @var string
     */
    public const PROVIDER_NAME = 'Computop';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_PAY_NOW = 'computopPayNow';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_CREDIT_CARD = 'computopCreditCard';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_DIRECT_DEBIT = 'computopDirectDebit';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_IDEAL = 'computopIdeal';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_PAYDIREKT = 'computopPaydirekt';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_PAY_PAL = 'computopPayPal';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_PAY_PAL_EXPRESS = 'computopPayPalExpress';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_SOFORT = 'computopSofort';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_EASY_CREDIT = 'computopEasyCredit';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_PAYU_CEE_SINGLE = 'computopPayuCeeSingle';

    /**
     * @var string
     */
    public const SUCCESS_STATUS = 'success';

    /**
     * @var string
     */
    public const SUCCESS_OK = 'OK';

    /**
     * @var string
     */
    public const AUTHORIZE_REQUEST_STATUS = 'AUTHORIZE_REQUEST';

    /**
     * @var string
     */
    public const DIRECT_DEBIT_DATE_FORMAT = 'd.m.Y';

    /**
     * @var int
     */
    public const PAY_PAL_NO_SHIPPING = 1;

    /**
     * @var string
     */
    public const INIT_METHOD = 'INIT';

    /**
     * @var string
     */
    public const TX_TYPE_ORDER = 'Order';

    /**
     * @var string
     */
    public const TX_TYPE_AUTH = 'Auth';

    /**
     * @var string
     */
    public const TX_TYPE_BAID = 'BAID';

    /**
     * @var string
     */
    public const PAY_PAL_EXPRESS_PAYPAL_METHOD = 'shortcut';

    /**
     * @var string
     */
    public const CRIF_PRODUCT_NAME_IDENT_CHECK_CONSUMER = 'IdentCheckConsumer';

    /**
     * @var string
     */
    public const CRIF_PRODUCT_NAME_QUICK_CHECK_CONSUMER = 'QuickCheckConsumer';

    /**
     * @var string
     */
    public const CRIF_PRODUCT_NAME_CREDIT_CHECK_CONSUMER = 'CreditCheckConsumer';

    /**
     * @var string
     */
    public const CRIF_PRODUCT_NAME_QUICK_CHECK_BUSINESS = 'QuickCheckBusiness';

    /**
     * @var string
     */
    public const CRIF_PRODUCT_NAME_CREDIT_CHECK_BUSINESS = 'CreditCheckBusiness';

    /**
     * @var string
     */
    public const CRIF_LEGAL_FORM_PERSON = 'PERSON';

    /**
     * @var string
     */
    public const CRIF_LEGAL_FORM_COMPANY = 'COMPANY';

    /**
     * @var string
     */
    public const CRIF_LEGAL_FORM_UNKNOWN = 'UNKNOWN';

    /**
     * @var string
     */
    public const COMPUTOP_MODULE_VERSION = 'Spryker – MV:1.0.1';
}
