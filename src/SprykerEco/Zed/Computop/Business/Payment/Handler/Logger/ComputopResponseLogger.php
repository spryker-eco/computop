<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Logger;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopApiLog;

class ComputopResponseLogger implements ComputopResponseLoggerInterface
{

    /**
     * {@inheritdoc}
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
