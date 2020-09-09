<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Processor;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;

interface NotificationProcessorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer
     *
     * @return void
     */
    public function processNotification(ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer): void;
}
