<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Mapper\Ideal;

use Generated\Shared\Transfer\ComputopIdealPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Business\Api\Mapper\AbstractMapper;

abstract class AbstractIdealMapper extends AbstractMapper
{
    /**
     * @return string
     */
    public function getMethodName()
    {
        return ComputopConfig::PAYMENT_METHOD_IDEAL;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopIdealPaymentTransfer
     */
    protected function createPaymentTransfer(OrderTransfer $orderTransfer)
    {
        return new ComputopIdealPaymentTransfer();
    }
}
