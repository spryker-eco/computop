<?php
/**
 * Copy over the following configs to your config
 */

use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\ComputopConstants;

$config[ComputopConstants::COMPUTOP_MERCHANT_ID] = 'COMPUTOP_MERCHANT_ID'; //Set up real data
$config[ComputopConstants::COMPUTOP_BLOWFISH_PASSWORD] = 'COMPUTOP_BLOWFISH_PASSWORD'; //Set up real data
$config[ComputopConstants::COMPUTOP_HMAC_PASSWORD] = 'COMPUTOP_HMAC_PASSWORD'; //Set up real data

$config[ComputopConstants::COMPUTOP_CREDIT_CARD_ORDER_ACTION] = 'https://www.computop-paygate.com/payssl.aspx';
$config[ComputopConstants::COMPUTOP_AUTHORIZE_ACTION] = 'https://www.computop-paygate.com/authorize.aspx';
$config[ComputopConstants::COMPUTOP_CAPTURE_ACTION] = 'https://www.computop-paygate.com/capture.aspx';
$config[ComputopConstants::COMPUTOP_REVERSE_ACTION] = 'https://www.computop-paygate.com/reverse.aspx';
$config[ComputopConstants::COMPUTOP_INQUIRE_ACTION] = 'https://www.computop-paygate.com/inquire.aspx';
$config[ComputopConstants::COMPUTOP_REFUND_ACTION] = 'https://www.computop-paygate.com/credit.aspx';

$config[ComputopConstants::COMPUTOP_PAY_PAL_ORDER_ACTION] = 'https://www.computop-paygate.com/paypal.aspx';
$config[ComputopConstants::COMPUTOP_DIRECT_DEBIT_ORDER_ACTION] = 'https://www.computop-paygate.com/paysdd.aspx';
$config[ComputopConstants::COMPUTOP_SOFORT_ORDER_ACTION] = 'https://www.computop-paygate.com/sofort.aspx';

$config[ComputopConstants::COMPUTOP_RESPONSE_MAC_REQUIRED] = [
    ComputopConfig::ORDER_METHOD, // Todo: add methods in case of Paygate form connection
]; //MAC is required for methods (to check MAC on response)

$config[ComputopConstants::COMPUTOP_PAYMENT_METHODS_WITHOUT_ORDER_CALL] = [
    ComputopConfig::PAYMENT_METHOD_SOFORT,
];
