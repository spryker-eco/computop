<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Computop;

interface ComputopConstants
{

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

}
