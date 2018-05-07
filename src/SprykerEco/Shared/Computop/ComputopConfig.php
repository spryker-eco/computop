<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Computop;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ComputopConfig extends AbstractBundleConfig
{
    const PROVIDER_NAME = 'Computop';
    const PAYMENT_METHOD_PAY_NOW = 'computopPayNow';
    const PAYMENT_METHOD_CREDIT_CARD = 'computopCreditCard';
    const PAYMENT_METHOD_DIRECT_DEBIT = 'computopDirectDebit';
    const PAYMENT_METHOD_IDEAL = 'computopIdeal';
    const PAYMENT_METHOD_PAYDIREKT = 'computopPaydirekt';
    const PAYMENT_METHOD_PAY_PAL = 'computopPayPal';
    const PAYMENT_METHOD_SOFORT = 'computopSofort';
    const PAYMENT_METHOD_EASY_CREDIT = 'computopEasyCredit';

    //Computop provider constants
    const CAPTURE_MANUAL_TYPE = 'MANUAL';
    const SUCCESS_STATUS = 'OK';
    const AUTHORIZE_REQUEST_STATUS = 'AUTHORIZE_REQUEST';
    const DIRECT_DEBIT_DATE_FORMAT = 'd.m.Y';
    const PAY_PAL_NO_SHIPPING = 1;
    const INIT_METHOD = 'INIT';
}
