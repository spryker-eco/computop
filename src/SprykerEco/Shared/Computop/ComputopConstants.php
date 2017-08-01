<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Computop;

interface ComputopConstants
{

    const PROVIDER_NAME = 'computop';

    const PAYMENT_METHOD_CREDIT_CARD = 'ComputopCreditCard';

    //Computop provider constants
    const CAPTURE_AUTO_TYPE = 'AUTO';
    const CAPTURE_MANUAL_TYPE = 'MANUAL';
    const CAPTURE_DELAY_TYPE = 696; //Delay in hours until the capture (whole number; 1 to 696).

    const RESPONSE_TYPE = 'encrypt';
    const TX_TYPE = 'Order';

    //Config data
    const COMPUTOP_MERCHANT_ID_KEY = 'COMPUTOP_MERCHANT_ID';
    const COMPUTOP_BLOWFISH_PASSWORD = 'COMPUTOP_BLOWFISH_PASSWORD';
    const COMPUTOP_HMAC_PASSWORD = 'COMPUTOP_HMAC_PASSWORD';

    const COMPUTOP_CREDIT_CARD_ORDER_ACTION = 'COMPUTOP_CREDIT_CARD_ORDER_ACTION';
    const COMPUTOP_CREDIT_CARD_AUTHORIZE_ACTION = 'COMPUTOP_CREDIT_CARD_AUTHORIZE_ACTION';

}
