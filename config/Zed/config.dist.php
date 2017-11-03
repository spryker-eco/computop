<?php
/**
 * Copy over the following configs to your config
 */

use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Shared\Oms\OmsConstants;
use Spryker\Shared\Sales\SalesConstants;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\ComputopConstants;

/**
 * Add new options
 */
$config[ComputopConstants::MERCHANT_ID] = 'COMPUTOP_MERCHANT_ID'; //Set up real data
$config[ComputopConstants::BLOWFISH_PASSWORD] = 'COMPUTOP_BLOWFISH_PASSWORD'; //Set up real data
$config[ComputopConstants::HMAC_PASSWORD] = 'COMPUTOP_HMAC_PASSWORD'; //Set up real data
$config[ComputopConstants::PAYDIREKT_SHOP_KEY] = 'PAYDIREKT_SHOP_KEY'; //Set up real data

$config[ComputopConstants::CREDIT_CARD_INIT_ACTION] = 'https://www.computop-paygate.com/payssl.aspx';
$config[ComputopConstants::AUTHORIZE_ACTION] = 'https://www.computop-paygate.com/authorize.aspx';
$config[ComputopConstants::CAPTURE_ACTION] = 'https://www.computop-paygate.com/capture.aspx';
$config[ComputopConstants::REVERSE_ACTION] = 'https://www.computop-paygate.com/reverse.aspx';
$config[ComputopConstants::INQUIRE_ACTION] = 'https://www.computop-paygate.com/inquire.aspx';
$config[ComputopConstants::REFUND_ACTION] = 'https://www.computop-paygate.com/credit.aspx';

$config[ComputopConstants::PAYPAL_INIT_ACTION] = 'https://www.computop-paygate.com/paypal.aspx';
$config[ComputopConstants::DIRECT_DEBIT_INIT_ACTION] = 'https://www.computop-paygate.com/paysdd.aspx';
$config[ComputopConstants::SOFORT_INIT_ACTION] = 'https://www.computop-paygate.com/sofort.aspx';
$config[ComputopConstants::PAYDIREKT_INIT_ACTION] = 'https://www.computop-paygate.com/paydirekt.aspx';
$config[ComputopConstants::IDEAL_INIT_ACTION] = 'https://www.computop-paygate.com/ideal.aspx';

$config[ComputopConstants::RESPONSE_MAC_REQUIRED] = [
    ComputopConfig::INIT_METHOD, // Todo: add methods in case of Paygate form connection
]; //MAC is required for methods (to check MAC on response)

$config[ComputopConstants::PAYMENT_METHODS_WITHOUT_ORDER_CALL] = [
    PaymentTransfer::COMPUTOP_SOFORT,
    PaymentTransfer::COMPUTOP_PAYDIREKT,
    PaymentTransfer::COMPUTOP_IDEAL,
];

/**
 * Update existing one options
 */
$config[OmsConstants::PROCESS_LOCATION] = [
    //...
    APPLICATION_ROOT_DIR . '/vendor/spryker-eco/computop/config/Zed/Oms',
];
$config[OmsConstants::ACTIVE_PROCESSES] = [
    //...
    //Add needed payment types
    'ComputopCreditCard01',
    'ComputopDirectDebit01',
    'ComputopPaydirekt01',
    'ComputopPayPal01',
    'ComputopSofort01',
    'ComputopIdeal01',
];
$config[SalesConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING] = [
    //...
    //Add needed payment types
    PaymentTransfer::COMPUTOP_CREDIT_CARD => 'ComputopCreditCard01',
    PaymentTransfer::COMPUTOP_DIRECT_DEBIT => 'ComputopDirectDebit01',
    PaymentTransfer::COMPUTOP_PAYDIREKT => 'ComputopPaydirekt01',
    PaymentTransfer::COMPUTOP_PAY_PAL => 'ComputopPayPal01',
    PaymentTransfer::COMPUTOP_SOFORT => 'ComputopSofort01',
    PaymentTransfer::COMPUTOP_IDEAL => 'ComputopIdeal01',
];
