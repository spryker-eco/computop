<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Computop;

interface ComputopConstants
{

    const PROVIDER_NAME = 'Computop';
    const PAYMENT_METHOD_CREDIT_CARD = 'ComputopCreditCard';
    const PAYMENT_METHOD_PAY_PAL = 'ComputopPayPal';
    const PAYMENT_METHOD_DIRECT_DEBIT = 'ComputopDirectDebit';
    const PAYMENT_METHOD_SOFORT = 'ComputopSofort';

    //Events
    const COMPUTOP_OMS_EVENT_CAPTURE = 'capture';

    //Computop provider constants
    const CAPTURE_AUTO_TYPE = 'AUTO';
    const CAPTURE_MANUAL_TYPE = 'MANUAL';
    const CAPTURE_DELAY_TYPE = 696; //Delay in hours until the capture (whole number; 1 to 696).

    const RESPONSE_TYPE = 'encrypt';
    const TX_TYPE_ORDER = 'Order';

    const ORDER_DESC_SUCCESS = 'Test:0000';
    const ORDER_DESC_ERROR = 'Test:0305';

    const ETI_ID = '0.0.1'; //Parameter is requested by Computop
    const FINISH_AUTH = 'Y'; //Only with ETM: Transmit value <Y> in order to stop the renewal of guaranteed authorizations and rest amounts after partial captures.

    const SUCCESS_STATUS = 'OK';

    //Config data
    const COMPUTOP_MERCHANT_ID = 'COMPUTOP_MERCHANT_ID';
    const COMPUTOP_BLOWFISH_PASSWORD = 'COMPUTOP_BLOWFISH_PASSWORD';
    const COMPUTOP_HMAC_PASSWORD = 'COMPUTOP_HMAC_PASSWORD';

    const COMPUTOP_CREDIT_CARD_ORDER_ACTION = 'COMPUTOP_CREDIT_CARD_ORDER_ACTION';
    const COMPUTOP_AUTHORIZE_ACTION = 'COMPUTOP_AUTHORIZE_ACTION';
    const COMPUTOP_CAPTURE_ACTION = 'COMPUTOP_CAPTURE_ACTION';
    const COMPUTOP_REVERSE_ACTION = 'COMPUTOP_REVERSE_ACTION';
    const COMPUTOP_INQUIRE_ACTION = 'COMPUTOP_INQUIRE_ACTION';
    const COMPUTOP_REFUND_ACTION = 'COMPUTOP_REFUND_ACTION';

    const COMPUTOP_PAY_PAL_ORDER_ACTION = 'COMPUTOP_PAY_PAL_ORDER_ACTION';
    const COMPUTOP_DIRECT_DEBIT_ORDER_ACTION = 'COMPUTOP_DIRECT_DEBIT_ORDER_ACTION';
    const COMPUTOP_SOFORT_ORDER_ACTION = 'COMPUTOP_SOFORT_ORDER_ACTION';

    const COMPUTOP_RESPONSE_MAC_REQUIRED = 'COMPUTOP_RESPONSE_MAC_REQUIRED';

    const COMPUTOP_PAYMENT_METHODS_WITHOUT_ORDER_CALL = 'COMPUTOP_PAYMENT_METHODS_WITHOUT_ORDER_CALL';

    const ORDER_METHOD = 'ORDER';
    const AUTHORIZE_METHOD = 'AUTHORIZE';
    const CAPTURE_METHOD = 'CAPTURE';
    const REVERSE_METHOD = 'REVERSE';
    const INQUIRE_METHOD = 'INQUIRE';
    const REFUND_METHOD = 'REFUND';

}
