<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Logger;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopApiLog;

class ComputopResponseLogger implements LoggerInterface
{
    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     * @param string $method
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopApiLog
     */
    public function log(ComputopResponseHeaderTransfer $header, $method)
    {
        $logEntity = new SpyPaymentComputopApiLog();
        $logEntity->fromArray($header->toArray());
        $logEntity->setMethod($method);
        $logEntity->save();

        return $logEntity;
    }
}
