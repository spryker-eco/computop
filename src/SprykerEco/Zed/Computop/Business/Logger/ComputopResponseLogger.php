<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Logger;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopApiLog;

class ComputopResponseLogger implements LoggerInterface
{
    /**
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $header
     * @param string $method
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopApiLog
     */
    public function log(ComputopApiResponseHeaderTransfer $header, $method): SpyPaymentComputopApiLog
    {
        $logEntity = new SpyPaymentComputopApiLog();
        $logEntity->fromArray($header->toArray());
        $logEntity->setMethod($method);
        $logEntity->save();

        return $logEntity;
    }
}
