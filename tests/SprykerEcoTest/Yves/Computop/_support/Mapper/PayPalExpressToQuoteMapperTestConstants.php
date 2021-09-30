<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Yves\Computop\Mapper;

interface PayPalExpressToQuoteMapperTestConstants
{
    /**
     * @var string
     */
    public const MERCHANT_ID_SHORT_VALUE = 'spryker_test';

    /**
     * @var int
     */
    public const PAY_ID_VALUE = 123;

    /**
     * @var string
     */
    public const TRANS_ID_VALUE = '4787e38abec06282a826ec76cbdfb95e';

    /**
     * @var int
     */
    public const CODE_VALUE = 0;

    /**
     * @var string
     */
    public const STATUS_VALUE = 'AUTHORIZE_REQUEST';

    /**
     * @var string
     */
    public const FIRST_NAME_VALUE = 'FirstName';

    /**
     * @var string
     */
    public const LAST_NAME_VALUE = 'LastName';

    /**
     * @var string
     */
    public const EMAIL_VALUE = 'email@spryker.local';

    /**
     * @var string
     */
    public const ADDRESS_STREET_VALUE = 'test street';

    /**
     * @var string
     */
    public const ADDRESS_COUNTRY_CODE_VALUE = 'DE';

    /**
     * @var string
     */
    public const ADDRESS_CITY_VALUE = 'Berlin';

    /**
     * @var int
     */
    public const ADDR_ZIP_VALUE = 65001;

    /**
     * @var string
     */
    public const BILLING_NAME_VALUE = 'BillingName';

    /**
     * @var string
     */
    public const BILLING_ADDRESS_STREET_VALUE = 'test billing street';

    /**
     * @var string
     */
    public const BILLING_ADDRESS_COUNTRY_CODE_VALUE = 'DE';

    /**
     * @var string
     */
    public const BILLING_ADDRESS_CITY_VALUE = 'Berlin';

    /**
     * @var int
     */
    public const BILLING_ADDRESS_ZIP_VALUE = 65002;
}
