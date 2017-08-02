<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model;

abstract class AbstractComputop
{

    const MAC_SEPARATOR = '*';
    const DATA_SEPARATOR = '&';
    const DATA_SUB_SEPARATOR = '=';

    const TRANS_ID = 'TransID';
    const AMOUNT = 'Amount';
    const CURRENCY = 'Currency';
    const URL_SUCCESS = 'URLSuccess';
    const URL_FAILURE = 'URLFailure';
    const CAPTURE = 'Capture';
    const RESPONSE = 'Response';
    const MAC = 'MAC';
    const TX_TYPE = 'TxType';
    const ORDER_DESC = 'OrderDesc';
    const PAY_ID = 'PayID';

    const MID = 'mid';
    const STATUS = 'Status';
    const DESCRIPTION = 'Description';
    const CODE = 'Code';
    const XID = 'XID';
    const TYPE = 'Type';
    const PCN_R = 'PCNr';
    const CC_EXPIRY = 'CCExpiry';
    const CC_BRAND = 'CCBrand';

}
