<?php
/**
 * Copy over the following configs to your config
 */

use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\ComputopConstants;

$config[ComputopConstants::MERCHANT_ID] = 'COMPUTOP_MERCHANT_ID'; //Set up real data
$config[ComputopConstants::BLOWFISH_PASSWORD] = 'COMPUTOP_BLOWFISH_PASSWORD'; //Set up real data
$config[ComputopConstants::HMAC_PASSWORD] = 'COMPUTOP_HMAC_PASSWORD'; //Set up real data
$config[ComputopConstants::PAYDIREKT_SHOP_KEY] = 'PAYDIREKT_SHOP_KEY'; //Set up real data

$config[ComputopConstants::CREDIT_CARD_ORDER_ACTION] = 'https://www.computop-paygate.com/payssl.aspx';
$config[ComputopConstants::AUTHORIZE_ACTION] = 'https://www.computop-paygate.com/authorize.aspx';
$config[ComputopConstants::CAPTURE_ACTION] = 'https://www.computop-paygate.com/capture.aspx';
$config[ComputopConstants::REVERSE_ACTION] = 'https://www.computop-paygate.com/reverse.aspx';
$config[ComputopConstants::INQUIRE_ACTION] = 'https://www.computop-paygate.com/inquire.aspx';
$config[ComputopConstants::REFUND_ACTION] = 'https://www.computop-paygate.com/credit.aspx';

$config[ComputopConstants::PAY_PAL_ORDER_ACTION] = 'https://www.computop-paygate.com/paypal.aspx';
$config[ComputopConstants::DIRECT_DEBIT_ORDER_ACTION] = 'https://www.computop-paygate.com/paysdd.aspx';
$config[ComputopConstants::SOFORT_ORDER_ACTION] = 'https://www.computop-paygate.com/sofort.aspx';
$config[ComputopConstants::PAYDIREKT_ORDER_ACTION] = 'https://www.computop-paygate.com/paydirekt.aspx';

$config[ComputopConstants::RESPONSE_MAC_REQUIRED] = [
    ComputopConfig::INIT_METHOD, // Todo: add methods in case of Paygate form connection
]; //MAC is required for methods (to check MAC on response)

$config[ComputopConstants::PAYMENT_METHODS_WITHOUT_ORDER_CALL] = [
    ComputopConfig::PAYMENT_METHOD_SOFORT,
];
