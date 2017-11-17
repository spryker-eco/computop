<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\EasyCredit;

use Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\AbstractPostPlaceMapper;

abstract class AbstractEasyCreditMapper extends AbstractPostPlaceMapper
{
    /**
     * @return string
     */
    public function getMethodName()
    {
        return ComputopConfig::PAYMENT_METHOD_EASY_CREDIT;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer
     */
    protected function createPaymentTransfer(OrderTransfer $orderTransfer)
    {
        return new ComputopEasyCreditPaymentTransfer();
    }
}
