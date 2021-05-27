<?php
//phpcs:ignoreFile

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Logger;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;

interface LoggerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $header
     * @param string $method
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopApiLog
     */
    public function log(ComputopApiResponseHeaderTransfer $header, $method);
}
