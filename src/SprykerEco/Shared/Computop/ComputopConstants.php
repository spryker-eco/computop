<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Computop;

interface ComputopConstants
{

    const PROVIDER_NAME = 'computop';

    const PAYMENT_METHOD_CREDIT_CARD = 'ComputopCreditCard';

    //Computop provider constants
    const CAPTURE_AUTO_TYPE = 'AUTO';
    const CAPTURE_MANUAL_TYPE = 'MANUAL';
    const CAPTURE_DELAY_TYPE = 696; //Delay in hours until the capture (whole number; 1 to 696).

    //Config data
    const COMPUTOP_MERCHANT_ID_KEY = 'COMPUTOP_MERCHANT_ID';
    const COMPUTOP_HASH_PASSWORD = 'COMPUTOP_MERCHANT_ID';

}
