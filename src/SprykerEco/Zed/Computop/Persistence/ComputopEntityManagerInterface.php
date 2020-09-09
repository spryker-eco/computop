<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Persistence;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;

interface ComputopEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer
     *
     * @return void
     */
    public function savePaymentComputopNotification(
        ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer
     *
     * @return void
     */
    public function updatePaymentComputopOrderItemNotificationStatus(
        ComputopApiResponseHeaderTransfer $computopApiResponseHeaderTransfer
    ): void;
}
