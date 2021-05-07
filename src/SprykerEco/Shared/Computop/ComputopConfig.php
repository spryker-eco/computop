<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Computop;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ComputopConfig extends AbstractSharedConfig
{
    public const PROVIDER_NAME = 'Computop';
    public const PAYMENT_METHOD_PAY_NOW = 'computopPayNow';
    public const PAYMENT_METHOD_CREDIT_CARD = 'computopCreditCard';
    public const PAYMENT_METHOD_DIRECT_DEBIT = 'computopDirectDebit';
    public const PAYMENT_METHOD_IDEAL = 'computopIdeal';
    public const PAYMENT_METHOD_PAYDIREKT = 'computopPaydirekt';
    public const PAYMENT_METHOD_PAY_PAL = 'computopPayPal';
    public const PAYMENT_METHOD_PAY_PAL_EXPRESS = 'computopPayPalExpress';
    public const PAYMENT_METHOD_SOFORT = 'computopSofort';
    public const PAYMENT_METHOD_EASY_CREDIT = 'computopEasyCredit';

    //Computop provider constants
    public const SUCCESS_STATUS = 'success';
    public const SUCCESS_OK = 'OK';
    public const AUTHORIZE_REQUEST_STATUS = 'AUTHORIZE_REQUEST';
    public const DIRECT_DEBIT_DATE_FORMAT = 'd.m.Y';
    public const PAY_PAL_NO_SHIPPING = 1;
    public const INIT_METHOD = 'INIT';

    public const TX_TYPE_ORDER = 'Order';
    public const TX_TYPE_AUTH = 'Auth';
    public const TX_TYPE_BAID = 'BAID';

    public const PAY_PAL_EXPRESS_PAYPAL_METHOD = 'shortcut';

    public const CRIF_PRODUCT_NAME_IDENT_CHECK_CONSUMER = 'IdentCheckConsumer';
    public const CRIF_PRODUCT_NAME_QUICK_CHECK_CONSUMER = 'QuickCheckConsumer';
    public const CRIF_PRODUCT_NAME_CREDIT_CHECK_CONSUMER = 'CreditCheckConsumer';
    public const CRIF_PRODUCT_NAME_QUICK_CHECK_BUSINESS = 'QuickCheckBusiness';
    public const CRIF_PRODUCT_NAME_CREDIT_CHECK_BUSINESS = 'CreditCheckBusiness';

    public const CRIF_LEGAL_FORM_PERSON = 'PERSON';
    public const CRIF_LEGAL_FORM_COMPANY = 'COMPANY';
    public const CRIF_LEGAL_FORM_UNKNOWN = 'UNKNOWN';

    public const COMPUTOP_MODULE_VERSION = 'Spryker – MV:1.0.1';
}
