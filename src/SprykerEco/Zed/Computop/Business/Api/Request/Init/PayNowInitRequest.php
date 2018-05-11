<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Request\Init;

use SprykerEco\Shared\Computop\ComputopConfig;

class PayNowInitRequest extends AbstractInitPaymentRequest implements InitRequestInterface
{
    /**
     * @return string
     */
    public function getMethodName()
    {
        return ComputopConfig::PAYMENT_METHOD_PAY_NOW;
    }
}
