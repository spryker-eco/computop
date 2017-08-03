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

    const ORDER_DESC_SUCCESS = 'Test:0000';
    const ORDER_DESC_ERROR = 'Test:0305';

    //Config data
    const COMPUTOP_MERCHANT_ID_KEY = 'COMPUTOP_MERCHANT_ID';
    const COMPUTOP_BLOWFISH_PASSWORD = 'COMPUTOP_BLOWFISH_PASSWORD';
    const COMPUTOP_HMAC_PASSWORD = 'COMPUTOP_HMAC_PASSWORD';

    const COMPUTOP_CREDIT_CARD_ORDER_ACTION = 'COMPUTOP_CREDIT_CARD_ORDER_ACTION';
    const COMPUTOP_CREDIT_CARD_AUTHORIZE_ACTION = 'COMPUTOP_CREDIT_CARD_AUTHORIZE_ACTION';

    //Field names
    const TRANS_ID_F_N = 'TransID';
    const AMOUNT_F_N = 'Amount';
    const CURRENCY_F_N = 'Currency';
    const URL_SUCCESS_F_N = 'URLSuccess';
    const URL_FAILURE_F_N = 'URLFailure';
    const CAPTURE_F_N = 'Capture';
    const RESPONSE_F_N = 'Response';
    const MAC_F_N = 'MAC';
    const TX_TYPE_F_N = 'TxType';
    const ORDER_DESC_F_N = 'OrderDesc';
    const PAY_ID_F_N = 'PayID';

    const MID_F_N = 'mid';
    const STATUS_F_N = 'Status';
    const DESCRIPTION_F_N = 'Description';
    const CODE_F_N = 'Code';
    const XID_F_N = 'XID';
    const TYPE_F_N = 'Type';
    const PCN_R_F_N = 'PCNr';
    const CC_EXPIRY_F_N = 'CCExpiry';
    const CC_BRAND_F_N = 'CCBrand';
    const REF_NR_F_N = 'refNr';

}
