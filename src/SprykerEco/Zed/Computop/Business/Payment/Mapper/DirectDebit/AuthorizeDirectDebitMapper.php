<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Mapper\DirectDebit;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConstants;

class AuthorizeDirectDebitMapper extends AbstractDirectDebitMapper
{

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPaymentTransfer
     *
     * @return array
     */
    public function getDataSubArray(TransferInterface $computopPaymentTransfer)
    {
        /** @var \Generated\Shared\Transfer\ComputopDirectDebitPaymentTransfer $computopPaymentTransfer */

        $dataSubArray[ComputopConstants::PAY_ID_F_N] = $computopPaymentTransfer->getPayId();
        $dataSubArray[ComputopConstants::TRANS_ID_F_N] = $computopPaymentTransfer->getTransId();
        $dataSubArray[ComputopConstants::AMOUNT_F_N] = $computopPaymentTransfer->getAmount();
        $dataSubArray[ComputopConstants::CURRENCY_F_N] = $computopPaymentTransfer->getCurrency();
        $dataSubArray[ComputopConstants::CAPTURE_F_N] = $computopPaymentTransfer->getCapture();
        $dataSubArray[ComputopConstants::MAC_F_N] = $computopPaymentTransfer->getMac();
        $dataSubArray[ComputopConstants::ORDER_DESC_F_N] = $computopPaymentTransfer->getOrderDesc();

        return $dataSubArray;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopDirectDebitPaymentTransfer
     */
    protected function createPaymentTransfer(OrderTransfer $orderTransfer)
    {
        $computopPaymentTransfer = parent::createPaymentTransfer($orderTransfer);
        $computopPaymentTransfer->setCapture(ComputopConstants::CAPTURE_MANUAL_TYPE);
        $computopPaymentTransfer->setOrderDesc($this->getOrderDesc($orderTransfer));

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    protected function getOrderDesc(OrderTransfer $orderTransfer)
    {
        return $this->computopService->getDescriptionValue(
            $orderTransfer->getItems()->getArrayCopy()
        );
    }

}