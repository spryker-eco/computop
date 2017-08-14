<?php
/**
 * Copy over the following configs to your config
 */

use SprykerEco\Shared\Computop\ComputopConstants;

$config[ComputopConstants::COMPUTOP_MERCHANT_ID_KEY] = 'COMPUTOP_MERCHANT_ID';
$config[ComputopConstants::COMPUTOP_BLOWFISH_PASSWORD_KEY] = 'COMPUTOP_BLOWFISH_PASSWORD';
$config[ComputopConstants::COMPUTOP_HMAC_PASSWORD_KEY] = 'COMPUTOP_HMAC_PASSWORD';

$config[ComputopConstants::COMPUTOP_CREDIT_CARD_ORDER_ACTION_KEY] = 'https://www.computop-paygate.com/payssl.aspx';
$config[ComputopConstants::COMPUTOP_CREDIT_CARD_AUTHORIZE_ACTION_KEY] = 'https://www.computop-paygate.com/authorize.aspx';
$config[ComputopConstants::COMPUTOP_CREDIT_CARD_CAPTURE_ACTION_KEY] = 'https://www.computop-paygate.com/capture.aspx';
$config[ComputopConstants::COMPUTOP_CREDIT_CARD_REVERSE_ACTION_KEY] = 'https://www.computop-paygate.com/reverse.aspx';
$config[ComputopConstants::COMPUTOP_CREDIT_CARD_INQUIRE_ACTION_KEY] = 'https://www.computop-paygate.com/inquire.aspx';
$config[ComputopConstants::COMPUTOP_CREDIT_CARD_REFUND_ACTION_KEY] = 'https://www.computop-paygate.com/credit.aspx';
