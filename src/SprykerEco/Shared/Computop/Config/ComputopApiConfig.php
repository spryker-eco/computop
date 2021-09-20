<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Computop\Config;

interface ComputopApiConfig
{
    /**
     * @var string
     */
    public const MERCHANT_ID = 'MerchantID';
    /**
     * @var string
     */
    public const MERCHANT_ID_SHORT = 'mid';
    /**
     * @var string
     */
    public const TRANS_ID = 'TransID';
    /**
     * @var string
     */
    public const AMOUNT = 'Amount';
    /**
     * @var string
     */
    public const AMOUNT_AUTH = 'AmountAuth';
    /**
     * @var string
     */
    public const AMOUNT_CAP = 'AmountCap';
    /**
     * @var string
     */
    public const AMOUNT_CRED = 'AmountCred';
    /**
     * @var string
     */
    public const LAST_STATUS = 'LastStatus';
    /**
     * @var string
     */
    public const CURRENCY = 'Currency';
    /**
     * @var string
     */
    public const URL_SUCCESS = 'URLSuccess';
    /**
     * @var string
     */
    public const URL_FAILURE = 'URLFailure';
    /**
     * @var string
     */
    public const URL_BACK = 'URLBack';
    /**
     * @var string
     */
    public const TEMPLATE = 'template';
    /**
     * @var string
     */
    public const URL_NOTIFY = 'URLNotify';
    /**
     * @var string
     */
    public const CAPTURE = 'Capture';
    /**
     * @var string
     */
    public const RESPONSE = 'Response';
    /**
     * @var string
     */
    public const MAC = 'MAC';
    /**
     * @var string
     */
    public const TX_TYPE = 'TxType';
    /**
     * @var string
     */
    public const ORDER_DESC = 'OrderDesc';
    /**
     * @var string
     */
    public const PAY_ID = 'PayID';
    /**
     * @var string
     */
    public const STATUS = 'Status';
    /**
     * @var string
     */
    public const DESCRIPTION = 'Description';
    /**
     * @var string
     */
    public const CODE = 'Code';
    /**
     * @var string
     */
    public const X_ID = 'XID';
    /**
     * @var string
     */
    public const CUSTOMER_TRANSACTION_ID = 'TID';
    /**
     * @var string
     */
    public const TYPE = 'Type';
    /**
     * @var string
     */
    public const PC_NR = 'PCNr';
    /**
     * @var string
     */
    public const CREDIT_CARD_NUMBER = 'CCNr';
    /**
     * @var string
     */
    public const CREDIT_CARD_VERIFICATION_CODE = 'CCCVC';
    /**
     * @var string
     */
    public const CREDIT_CARD_EXPIRY = 'CCExpiry';
    /**
     * @var string
     */
    public const CREDIT_CARD_BRAND = 'CCBrand';
    /**
     * @var string
     */
    public const MASKED_PAN = 'MaskedPan';
    /**
     * @var string
     */
    public const CAVV = 'CAVV';
    /**
     * @var string
     */
    public const ECI = 'ECI';
    /**
     * @var string
     */
    public const PLAIN = 'Plain';
    /**
     * @var string
     */
    public const REF_NR = 'RefNr';
    /**
     * @var string
     */
    public const INFO_TEXT = 'InfoText';
    /**
     * @var string
     */
    public const A_ID = 'AID';
    /**
     * @var string
     */
    public const CODE_EXT = 'CodeExt';
    /**
     * @var string
     */
    public const ERROR_TEXT = 'ErrorText';
    /**
     * @var string
     */
    public const TRANSACTION_ID = 'TransactionID';
    /**
     * @var string
     */
    public const FINISH_AUTH = 'FinishAuth';
    /**
     * @var string
     */
    public const ETI_ID = 'EtiId';
    /**
     * @var string
     */
    public const REQ_ID = 'ReqID';
    /**
     * @var string
     */
    public const DATA = 'Data';
    /**
     * @var string
     */
    public const LENGTH = 'Len';
    /**
     * @var string
     */
    public const NO_SHIPPING = 'NoShipping';
    /**
     * @var string
     */
    public const EMAIL = 'e-mail';
    /**
     * @var string
     */
    public const FIRST_NAME = 'firstname';
    /**
     * @var string
     */
    public const LAST_NAME = 'lastname';
    /**
     * @var string
     */
    public const CUSTOM = 'Custom';
    /**
     * @var string
     */
    public const PHONE = 'Phone';
    /**
     * @var string
     */
    public const BILLING_AGREEMENT_ID = 'BillingAgreementID';
    /**
     * @var string
     */
    public const BILLING_NAME = 'BillingName';
    /**
     * @var string
     */
    public const BILLING_ADDRESS_STREET = 'BillingAddrStreet';
    /**
     * @var string
     */
    public const BILLING_ADDRESS_STREET2 = 'BillingAddrStreet2';
    /**
     * @var string
     */
    public const BILLING_ADDRESS_CITY = 'BillingAddrCity';
    /**
     * @var string
     */
    public const BILLING_ADDRESS_STATE = 'BillingAddrState';
    /**
     * @var string
     */
    public const BILLING_ADDRESS_ZIP = 'BillingAddrZIP';
    /**
     * @var string
     */
    public const BILLING_ADDRESS_COUNTRY_CODE = 'BillingAddrCountryCode';
    /**
     * @var string
     */
    public const ADDRESS_STREET = 'AddrStreet';
    /**
     * @var string
     */
    public const ADDRESS_STREET_NR = 'AddrStreetNr';
    /**
     * @var string
     */
    public const ADDRESS_ADDITIONAL = 'AddrAddition';
    /**
     * @var string
     */
    public const ADDRESS_STREET2 = 'AddrStreet2';
    /**
     * @var string
     */
    public const ADDRESS_CITY = 'AddrCity';
    /**
     * @var string
     */
    public const ADDR_ZIP = 'AddrZip';
    /**
     * @var string
     */
    public const ADDR_EMAIL = 'Email';
    /**
     * @var string
     */
    public const ADDRESS_STATE = 'AddrState';
    /**
     * @var string
     */
    public const ADDRESS_ZIP = 'AddrZIP';
    /**
     * @var string
     */
    public const ADDRESS_COUNTRY_CODE = 'AddrCountryCode';
    /**
     * @var string
     */
    public const BIRTHDAY = 'Birthday';
    /**
     * @var string
     */
    public const AGE = 'Age';
    /**
     * @var string
     */
    public const PAYER_ID = 'PayerID';
    /**
     * @var string
     */
    public const IS_FINANCING = 'IsFinancing';
    /**
     * @var string
     */
    public const FINANCING_FEE_AMOUNT = 'FinancingFeeAmount';
    /**
     * @var string
     */
    public const FINANCING_MONTHLY_PAYMENT = 'FinancingMonthlyPayment';
    /**
     * @var string
     */
    public const FINANCING_TERM = 'FinancingTerm';
    /**
     * @var string
     */
    public const FINANCING_TOTAL_COST = 'FinancingTotalCost';
    /**
     * @var string
     */
    public const GROSS_AMOUNT = 'GrossAmount';
    /**
     * @var string
     */
    public const FEE_AMOUNT = 'FeeAmount';
    /**
     * @var string
     */
    public const SETTLE_AMOUNT = 'SettleAmount';
    /**
     * @var string
     */
    public const TAX_AMOUNT = 'TaxAmount';
    /**
     * @var string
     */
    public const EXCHANGE_RATE = 'ExchangeRate';
    /**
     * @var string
     */
    public const MC_FEE = 'mc_fee';
    /**
     * @var string
     */
    public const MC_GROSS = 'mc_gross';
    /**
     * @var string
     */
    public const MANDATE_ID = 'mandateid';
    /**
     * @var string
     */
    public const DATE_OF_SIGNATURE_ID = 'dtofsgntr';
    /**
     * @var string
     */
    public const BANK_ACCOUNT_IBAN = 'IBAN';
    /**
     * @var string
     */
    public const BANK_ACCOUNT_PBAN = 'pban';
    /**
     * @var string
     */
    public const BANK_ACCOUNT_BIC = 'bic';
    /**
     * @var string
     */
    public const ACCOUNT_OWNER = 'AccOwner';
    /**
     * @var string
     */
    public const ACCOUNT_BANK = 'AccBank';
    /**
     * @var string
     */
    public const MDT_SEQ_TYPE = 'mdtseqtype';
    /**
     * @var string
     */
    public const SHOP_API_KEY = 'shopApiKey';
    /**
     * @var string
     */
    public const SHOPPING_BASKET_AMOUNT = 'ShoppingBasketAmount';
    /**
     * @var string
     */
    public const SHOPPING_BASKET_CATEGORY = 'ShoppingBasketCategory';
    /**
     * @var string
     */
    public const SHIPPING_AMOUNT = 'shAmount';
    /**
     * @var string
     */
    public const SHIPPING_FIRST_NAME = 'sdFirstName';
    /**
     * @var string
     */
    public const SHIPPING_LAST_NAME = 'sdLastName';
    /**
     * @var string
     */
    public const SHIPPING_ZIP = 'sdZip';
    /**
     * @var string
     */
    public const SHIPPING_CITY = 'sdCity';
    /**
     * @var string
     */
    public const SHIPPING_COUNTRY_CODE = 'sdCountryCode';
    /**
     * @var string
     */
    public const SHIPPING_COMPANY = 'sdCompany';
    /**
     * @var string
     */
    public const SHIPPING_ADDRESS_ADDITION = 'sdAddressAddition';
    /**
     * @var string
     */
    public const SHIPPING_STREET = 'sdStreet';
    /**
     * @var string
     */
    public const SHIPPING_STREET_NUMBER = 'sdStreetNr';
    /**
     * @var string
     */
    public const SHIPPING_EMAIL = 'sdEMail';
    /**
     * @var string
     */
    public const AGE_ACCEPTED = 'AgeAccepted';
    /**
     * @var string
     */
    public const PAYMENT_PURPOSE = 'paymentPurpose';
    /**
     * @var string
     */
    public const PAYMENT_GUARANTEE = 'paymentGuarantee';
    /**
     * @var string
     */
    public const EVENT_TOKEN = 'EventToken';
    /**
     * @var string
     */
    public const EVENT_TOKEN_INIT = 'INT';
    /**
     * @var string
     */
    public const EVENT_TOKEN_GET = 'GET';
    /**
     * @var string
     */
    public const EVENT_TOKEN_AUTHORIZE = 'CON';
    /**
     * @var string
     */
    public const DATE = 'Date';
    /**
     * @var string
     */
    public const REASON = 'Reason';
    /**
     * @var string
     */
    public const REASON_FULL_CANCELL = 'WIDERRUF_VOLLSTAENDIG';
    /**
     * @var string
     */
    public const REASON_PART_CANCELL = 'WIDERRUF_TEILWEISE';
    /**
     * @var string
     */
    public const REASON_FULL_CANCELL_GUARANTEE = 'RUECKGABE_GARANTIE_GEWAEHRLEISTUNG';
    /**
     * @var string
     */
    public const REASON_PART_CANCELL_GUARANTEE = 'MINDERUNG_GARANTIE_GEWAEHRLEISTUNG';
    /**
     * @var int
     */
    public const PAYDIRECT_TRANS_ID_LENGTH = 20;
    /**
     * @var int
     */
    public const REQ_ID_LENGTH = 32;
    /**
     * @var string
     */
    public const PRODUCT_NAME = 'ProductName';
    /**
     * @var string
     */
    public const CUSTOMER_ID = 'CustomerID';
    /**
     * @var string
     */
    public const LEGAL_FORM = 'LegalForm';
    /**
     * @var string
     */
    public const IP_ADDRESS = 'IPAddr';
    /**
     * @var string
     */
    public const ISSUER_ID = 'IssuerID';
    /**
     * @var string
     */
    public const BILL_TO_CUSTOMER = 'billToCustomer';
    /**
     * @var string
     */
    public const SHIP_TO_CUSTOMER = 'shipToCustomer';
    /**
     * @var string
     */
    public const BILLING_ADDRESS = 'billingAddress';
    /**
     * @var string
     */
    public const SHIPPING_ADDRESS = 'shippingAddress';
    /**
     * @var string
     */
    public const CREDENTIAL_ON_FILE = 'credentialOnFile';
    /**
     * @var string
     */
    public const BROWSER_INFO = 'browserInfo';
    /**
     * @var string
     */
    public const MSG_VER = 'MsgVer';
    /**
     * @var string
     */
    public const PSD2_MSG_VERSION = '2.0';
    /**
     * @var string
     */
    public const UNSCHEDULED_CUSTOMER_INITIATED_TRANSACTION = 'CIT';
    /**
     * @var string
     */
    public const NOTIFICATION_PARAMETER_PAYMENT_TYPE = 'type';
    /**
     * Specification:
     * - PayPal payment method field for total taxes.
     *
     * @api
     * @var string
     */
    public const TAX_TOTAL = 'TaxTotal';

    /**
     * Specification:
     * - PayPal payment method field for summary (without taxes and shipping).
     *
     * @api
     * @var string
     */
    public const ITEM_TOTAL = 'ItemTotal';
    /**
     * @var string
     */
    public const PAY_PAL_METHOD = 'payPalMethod';
}
