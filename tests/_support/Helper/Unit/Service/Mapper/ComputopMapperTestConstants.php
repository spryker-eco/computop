<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Computop\Helper\Unit\Service\Mapper;

interface ComputopMapperTestConstants
{

    const PAY_ID_VALUE = 'PAY_ID_VALUE';
    const TRANS_ID_VALUE = 'TRANS_ID_VALUE';
    const MERCHANT_ID_VALUE = 'MERCHANT_ID_VALUE';
    const AMOUNT_VALUE = 1;
    const CURRENCY_VALUE = 'USD';

    const STATUS_VALUE = 'OK';
    const CODE_VALUE = '000000';

    const EXPECTED_MAC = 'PAY_ID_VALUE*TRANS_ID_VALUE*MERCHANT_ID_VALUE*1*USD';
    const EXPECTED_MAC_RESPONSE = 'PAY_ID_VALUE*TRANS_ID_VALUE*MERCHANT_ID_VALUE*OK*000000';
    const EXPECTED_PLAINTEXT = 'test_key=test_value';

}
