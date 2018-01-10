<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\EasyCredit;

use DateTime;
use Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\AbstractPostPlaceMapper;

abstract class AbstractEasyCreditMapper extends AbstractPostPlaceMapper
{
    const DATE_TIME_FORMAT = 'Y-m-d';

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
        $computopEasyCreditTransfer = new ComputopEasyCreditPaymentTransfer();
        $dateTime = new DateTime($orderTransfer->getCreatedAt());
        $computopEasyCreditTransfer->setDate($dateTime->format(self::DATE_TIME_FORMAT));

        return $computopEasyCreditTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPaymentTransfer
     *
     * @return array
     */
    public function getDataSubArray(TransferInterface $computopPaymentTransfer)
    {
        /** @var \Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer $computopPaymentTransfer */
        $dataSubArray[ComputopApiConfig::PAY_ID] = $computopPaymentTransfer->getPayId();
        $dataSubArray[ComputopApiConfig::TRANS_ID] = $computopPaymentTransfer->getTransId();
        $dataSubArray[ComputopApiConfig::AMOUNT] = $computopPaymentTransfer->getAmount();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $computopPaymentTransfer->getCurrency();
        $dataSubArray[ComputopApiConfig::MAC] = $computopPaymentTransfer->getMac();

        return $dataSubArray;
    }
}
