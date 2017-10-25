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
    const PAYMENT_METHOD_CREDIT_CARD = 'ComputopCreditCard';
    const PAYMENT_METHOD_PAY_PAL = 'ComputopPayPal';
    const PAYMENT_METHOD_DIRECT_DEBIT = 'ComputopDirectDebit';
    const PAYMENT_METHOD_SOFORT = 'ComputopSofort';
    const PAYMENT_METHOD_PAYDIREKT = 'ComputopPaydirekt';

    //Computop provider constants
    const CAPTURE_MANUAL_TYPE = 'MANUAL';
    const SUCCESS_STATUS = 'OK';

    const INIT_METHOD = 'INIT';
}
