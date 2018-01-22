<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\EasyCredit;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class RefundEasyCreditMapper extends AbstractEasyCreditMapper
{
    const DATE_TIME_FORMAT = 'YYYY-mm-dd';
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPaymentTransfer
     *
     * @return array
     */
    public function getDataSubArray(TransferInterface $computopPaymentTransfer)
    {
        /** @var \Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer $computopPaymentTransfer */
        $dataSubArray = parent::getDataSubArray($computopPaymentTransfer);
        $dataSubArray[ComputopApiConfig::DATE] = $computopPaymentTransfer->getDate();

        $dataSubArray[ComputopApiConfig::REASON] = ComputopApiConfig::REASON_FULL_CANCELL;

        return $dataSubArray;
    }
}
