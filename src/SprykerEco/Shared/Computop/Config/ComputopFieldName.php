<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Computop\Config;

interface ComputopApiConfig
{
    const MERCHANT_ID = 'MerchantID';
    const TRANS_ID = 'TransID';
    const AMOUNT = 'Amount';
    const AMOUNT_AUTH = 'AmountAuth';
    const AMOUNT_CAP = 'AmountCap';
    const AMOUNT_CRED = 'AmountCred';
    const LAST_STATUS = 'LastStatus';
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
    const PC_NR = 'PCNr';
    const CC_EXPIRY = 'CCExpiry';
    const CC_BRAND = 'CCBrand';
    const MASKED_PAN = 'MaskedPan';
    const CAVV = 'CAVV';
    const ECI = 'ECI';
    const PLAIN = 'Plain';
    const REF_NR = 'RefNr';
    const INFO_TEXT = 'InfoText';
    const A_ID = 'AID';
    const CODE_EXT = 'CodeExt';
    const ERROR_TEXT = 'ErrorText';
    const TRANSACTION_ID = 'TransactionID';
    const FINISH_AUTH = 'FinishAuth';
    const ETI_ID = 'EtiId';
    const REQ_ID = 'ReqID';
    const DATA = 'Data';
    const LENGTH = 'Len';
    const NO_SHIPPING = 'NoShipping';
    const EMAIL = 'e-mail';
    const FIRST_NAME = 'firstname';
    const LAST_NAME = 'lastname';
    const CUSTOM = 'Custom';
    const PHONE = 'Phone';
    const BILLING_AGREEMENT_ID = 'BillingAgreementID';
    const BILLING_NAME = 'BillingName';
    const BILLING_ADDRESS_STREET = 'BillingAddrStreet';
    const BILLING_ADDRESS_STREET2 = 'BillingAddrStreet2';
    const BILLING_ADDRESS_CITY = 'BillingAddrCity';
    const BILLING_ADDRESS_STATE = 'BillingAddrState';
    const BILLING_ADDRESS_ZIP = 'BillingAddrZIP';
    const BILLING_ADDRESS_COUNTRY_CODE = 'BillingAddrCountryCode';
    const ADDRESS_STREET = 'AddrStreet';
    const ADDRESS_STREET2 = 'AddrStreet2';
    const ADDRESS_CITY = 'AddrCity';
    const ADDRESS_STATE = 'AddrState';
    const ADDRESS_ZIP = 'AddrZIP';
    const ADDRESS_COUNTRY_CODE = 'AddrCountryCode';
    const BIRTHDAY = 'Birthday';
    const AGE = 'Age';
    const PAYER_ID = 'PayerID';
    const IS_FINANCING = 'IsFinancing';
    const FINANCING_FEE_AMOUNT = 'FinancingFeeAmount';
    const FINANCING_MONTHLY_PAYMENT = 'FinancingMonthlyPayment';
    const FINANCING_TERM = 'FinancingTerm';
    const FINANCING_TOTAL_COST = 'FinancingTotalCost';
    const GROSS_AMOUNT = 'GrossAmount';
    const FEE_AMOUNT = 'FeeAmount';
    const SETTLE_AMOUNT = 'SettleAmount';
    const TAX_AMOUNT = 'TaxAmount';
    const EXCHANGE_RATE = 'ExchangeRate';
    const MC_FEE = 'mc_fee';
    const MC_GROSS = 'mc_gross';
    const MANDATE_ID = 'mandateid';
    const DATE_OF_SIGNATURE_ID = 'dtofsgntr';
    const I_B_A_N = 'IBAN';
    const P_B_A_N = 'pban';
    const B_I_C = 'bic';
    const ACCOUNT_OWNER = 'AccOwner';
    const ACCOUNT_BANK = 'AccBank';
    const MDT_SEQ_TYPE = 'mdtseqtype';
    const SHOP_API_KEY = 'shopApiKey';
    const SHOPPING_BASKET_AMOUNT = 'ShoppingBasketAmount';
    const SHOPPING_BASKET_CATEGORY = 'ShoppingBasketCategory';
    const SHIPPING_AMOUNT = 'shAmount';
    const SHIPPING_FIRST_NAME = 'sdFirstName';
    const SHIPPING_LAST_NAME = 'sdLastName';
    const SHIPPING_ZIP = 'sdZip';
    const SHIPPING_CITY = 'sdCity';
    const SHIPPING_COUNTRY_CODE = 'sdCountryCode';
    const SHIPPING_COMPANY = 'sdCompany';
    const SHIPPING_ADDRESS_ADDITION = 'sdAddressAddition';
    const SHIPPING_STREET = 'sdStreet';
    const SHIPPING_STREET_NUMBER = 'sdStreetNr';
    const SHIPPING_EMAIL = 'sdEMail';
    const AGE_ACCEPTED = 'AgeAccepted';
    const PAYMENT_PURPOSE = 'paymentPurpose';
    const PAYMENT_GUARANTEE = 'paymentGuarantee';
}
