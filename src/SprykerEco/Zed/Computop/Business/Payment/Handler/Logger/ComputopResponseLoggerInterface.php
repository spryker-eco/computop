<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Logger;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;

interface ComputopResponseLoggerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $header
     * @param string $method
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopApiLog
     */
    public function log(ComputopApiResponseHeaderTransfer $header, $method);
}
