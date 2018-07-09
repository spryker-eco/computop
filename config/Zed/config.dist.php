<?php
/**
 * Copy over the following configs to your config
 */

use Spryker\Shared\Oms\OmsConstants;
use Spryker\Shared\Sales\SalesConstants;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Shared\ComputopApi\ComputopApiConfig;
use SprykerEco\Shared\ComputopApi\ComputopApiConstants;

/**
 * Add new options
 */
$config[ComputopConstants::HMAC_PASSWORD] = 'COMPUTOP_HMAC_PASSWORD'; //Set up real data
$config[ComputopConstants::PAYDIREKT_SHOP_KEY] = 'PAYDIREKT_SHOP_KEY'; //Set up real data

$config[ComputopConstants::PAY_NOW_INIT_ACTION] = 'https://www.computop-paygate.com/paynow.aspx';
$config[ComputopConstants::CREDIT_CARD_INIT_ACTION] = 'https://www.computop-paygate.com/payssl.aspx';
$config[ComputopConstants::PAYPAL_INIT_ACTION] = 'https://www.computop-paygate.com/paypal.aspx';
$config[ComputopConstants::DIRECT_DEBIT_INIT_ACTION] = 'https://www.computop-paygate.com/paysdd.aspx';
$config[ComputopConstants::SOFORT_INIT_ACTION] = 'https://www.computop-paygate.com/sofort.aspx';
$config[ComputopConstants::PAYDIREKT_INIT_ACTION] = 'https://www.computop-paygate.com/paydirekt.aspx';
$config[ComputopConstants::IDEAL_INIT_ACTION] = 'https://www.computop-paygate.com/ideal.aspx';
$config[ComputopConstants::EASY_CREDIT_INIT_ACTION] = 'https://www.computop-paygate.com/easyCredit.aspx';

$config[ComputopApiConstants::CRIF_ACTION] = 'https://www.computop-paygate.com/deltavista.aspx';
$config[ComputopApiConstants::CRIF_PRODUCT_NAME] = 'IdentCheckConsumer';
$config[ComputopApiConstants::CRIF_LEGAL_FORM] = 'PERSON';

$config[ComputopApiConstants::MERCHANT_ID] = 'COMPUTOP_MERCHANT_ID'; //Set up real data
$config[ComputopApiConstants::BLOWFISH_PASSWORD] = 'COMPUTOP_BLOWFISH_PASSWORD'; //Set up real data
$config[ComputopApiConstants::HMAC_PASSWORD] = 'COMPUTOP_HMAC_PASSWORD'; //Set up real data
$config[ComputopApiConstants::EASY_CREDIT_STATUS_ACTION] = 'https://www.computop-paygate.com/easyCreditDirect.aspx';
$config[ComputopApiConstants::EASY_CREDIT_AUTHORIZE_ACTION] = 'https://www.computop-paygate.com/easyCreditDirect.aspx';
$config[ComputopApiConstants::AUTHORIZE_ACTION] = 'https://www.computop-paygate.com/authorize.aspx';
$config[ComputopApiConstants::CAPTURE_ACTION] = 'https://www.computop-paygate.com/capture.aspx';
$config[ComputopApiConstants::REVERSE_ACTION] = 'https://www.computop-paygate.com/reverse.aspx';
$config[ComputopApiConstants::INQUIRE_ACTION] = 'https://www.computop-paygate.com/inquire.aspx';
$config[ComputopApiConstants::REFUND_ACTION] = 'https://www.computop-paygate.com/credit.aspx';

$config[ComputopApiConstants::RESPONSE_MAC_REQUIRED] = [
    ComputopConfig::INIT_METHOD, // Todo: add methods in case of Paygate form connection
]; //MAC is required for methods (to check MAC on response)

$config[ComputopConstants::PAYMENT_METHODS_WITHOUT_ORDER_CALL] = [
    ComputopConfig::PAYMENT_METHOD_SOFORT,
    ComputopConfig::PAYMENT_METHOD_PAYDIREKT,
    ComputopConfig::PAYMENT_METHOD_IDEAL,
    ComputopConfig::PAYMENT_METHOD_PAY_NOW,
    ComputopConfig::PAYMENT_METHOD_CREDIT_CARD,
    ComputopConfig::PAYMENT_METHOD_PAY_PAL,
    ComputopConfig::PAYMENT_METHOD_DIRECT_DEBIT,
    ComputopConfig::PAYMENT_METHOD_EASY_CREDIT,
];

$config[ComputopApiConstants::PAYMENT_METHODS_CAPTURE_TYPES] = [
    ComputopApiConfig::PAYMENT_METHOD_SOFORT => ComputopApiConfig::CAPTURE_TYPE_MANUAL,
    ComputopApiConfig::PAYMENT_METHOD_PAYDIREKT => ComputopApiConfig::CAPTURE_TYPE_MANUAL,
    ComputopApiConfig::PAYMENT_METHOD_CREDIT_CARD => ComputopApiConfig::CAPTURE_TYPE_MANUAL,
    ComputopApiConfig::PAYMENT_METHOD_PAY_NOW => ComputopApiConfig::CAPTURE_TYPE_MANUAL,
    ComputopApiConfig::PAYMENT_METHOD_PAY_PAL => ComputopApiConfig::CAPTURE_TYPE_MANUAL,
    ComputopApiConfig::PAYMENT_METHOD_DIRECT_DEBIT => ComputopApiConfig::CAPTURE_TYPE_MANUAL,
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
    'ComputopPayNowd01',
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
    ComputopConfig::PAYMENT_METHOD_PAY_NOW => 'ComputopPayNow01',
    ComputopConfig::PAYMENT_METHOD_CREDIT_CARD => 'ComputopCreditCard01',
    ComputopConfig::PAYMENT_METHOD_DIRECT_DEBIT => 'ComputopDirectDebit01',
    ComputopConfig::PAYMENT_METHOD_PAYDIREKT => 'ComputopPaydirekt01',
    ComputopConfig::PAYMENT_METHOD_PAY_PAL => 'ComputopPayPal01',
    ComputopConfig::PAYMENT_METHOD_SOFORT => 'ComputopSofort01',
    ComputopConfig::PAYMENT_METHOD_IDEAL => 'ComputopIdeal01',
];

$config[ComputopConstants::CRIF_GREEN_AVAILABLE_PAYMENT_METHODS] = [
    ComputopConfig::PAYMENT_METHOD_SOFORT,
    ComputopConfig::PAYMENT_METHOD_PAYDIREKT,
    ComputopConfig::PAYMENT_METHOD_IDEAL,
    ComputopConfig::PAYMENT_METHOD_CREDIT_CARD,
    ComputopConfig::PAYMENT_METHOD_PAY_NOW,
    ComputopConfig::PAYMENT_METHOD_PAY_PAL,
    ComputopConfig::PAYMENT_METHOD_DIRECT_DEBIT,
    ComputopConfig::PAYMENT_METHOD_EASY_CREDIT,
];
$config[ComputopConstants::CRIF_YELLOW_AVAILABLE_PAYMENT_METHODS] = [
    ComputopConfig::PAYMENT_METHOD_CREDIT_CARD,
    ComputopConfig::PAYMENT_METHOD_PAY_NOW,
    ComputopConfig::PAYMENT_METHOD_PAY_PAL,
];
$config[ComputopConstants::CRIF_RED_AVAILABLE_PAYMENT_METHODS] = [
    ComputopConfig::PAYMENT_METHOD_CREDIT_CARD,
];
