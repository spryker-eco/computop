<?php

namespace SprykerEco\Zed\Computop\Persistence;

use Generated\Shared\Transfer\ComputopPaymentComputopTransfer;

interface ComputopRepositoryInterface
{
    /**
     * @param string $transactionId
     *
     * @return ComputopPaymentComputopTransfer
     */
    public function getComputopPaymentByComputopTransId(string $transactionId): ComputopPaymentComputopTransfer;
}
